<?php

  function searchClassTitleSectionByTeacher($page, $title, $teacherFName, $teacherLName){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT class_course.title, class_section.class_section_number
                FROM professor_account, teaches, class_section, has, class_course
                WHERE professor_account.professor_id = professor_account.professor_id
                  AND teaches.section_id = class_section.section_id
                  AND class_section.section_id = has.section_id
                  AND has.class_id = class_course.class_id
                  AND class_course.title LIKE :title
                  AND professor_account.first_name LIKE :teacherFName
                  AND professor_account.last_name LIKE :teacherLName";


    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':title', '%'.$title.'%');
    $stmt->bindValue(':teacherFName', '%'.$teacherFName.'%');
    $stmt->bindValue(':teacherLName', '%'.$teacherLName.'%');

    try{
      // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
      $stmt->execute();
    }catch (Exception $e){
      // fail JSON response
      $response = array();
      $response["message"] = "ERROR SELECTING: ".$e->getMessage();
      $response["success"] = 0;
      // echo json_encode($response);
      // die();
      return json_encode($response);
    }

    $pdo = null;
    $response = array();
    $response["message"] = "Success SELECTING from Question";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>