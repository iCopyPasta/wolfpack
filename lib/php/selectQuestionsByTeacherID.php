<?php
  /*
    //TODO: documentation and usage

    arguments:  teacherID - the teacher_id of the teacher to get all classes for
    return: JSON obj containing all attributes for question
    
    Example Usage:
    $retVal = searchClassesTaught('1');
    $retVal = json_decode($retVal);
    $removeZerothIndex = $retVal;
    unset($removeZerothIndex[0]);

    foreach($removeZerothIndex as $value){
      $classId = $retVal[1]->class_id;
      $title = $retVal[1]->title;
      $description = $retVal[1]->description;
      $offering = $retVal[1]->offering;
      $location = $retVal[1]->location;
      echo $classId.'<br>'.$title.'<br>'.$description.'<br>'.$offering.'<br>'.$location.'<br>';
    }

  */
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