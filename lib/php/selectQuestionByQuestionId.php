<?php

  function selectQuestionByQuestionId($questionID){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT question.question_id,question.teacher_id,question.question_type,question.description,question.potential_answers,question.correct_answers FROM question WHERE question.question_id = $questionID";

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