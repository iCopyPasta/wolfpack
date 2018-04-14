<?php

  function selectClassByClassId($classID){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT class_course_section.class_id,class_course_section.title,class_course_section.description,class_course_section.offering,class_course_section.location FROM class_course_section WHERE class_course_section.class_id = $classID";

    $stmt = $pdo->prepare($sql);


    try{
      // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
      $stmt->execute();
    }catch (Exception $e){
      // fail JSON response
      $response = array();
      $response["message"] = "ERROR SELECTING: ".$e->getMessage();
      $response["success"] = 0;
       echo json_encode($response);
       die();
      return json_encode($response);
    }

    $pdo = null;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($retVal);
  }

?>