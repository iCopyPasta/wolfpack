<?php

  function searchQuestionsByTeacherID($teacherID){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT question.question_id,question.teacher_id,question.question_type,question.description,question.potential_answers,question.correct_answers FROM question, teacher_account WHERE teacher_account.teacher_id = :teacherID AND question.teacher_id = :teacherID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':teacherID', $teacherID);


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
    $response["message"] = "Success SELECTING from teaches, class_course_section";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>