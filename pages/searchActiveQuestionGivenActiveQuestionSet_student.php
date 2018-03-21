<?php
  /*
    function searchActiveSessionByClassAndStudent
    arguments:  page - the current page
                rowsPerPage - rows per page
                class_id -
                student_id -
    return: JSON obj containing question_set_id

    //TODO: the following documentation and usage
    Example Usage:
    $retVal = searchQuestionsByTeacher_teacher('1', '1', '1', 'Sukmoon', 'Chang');
    $retVal = json_decode($retVal);
    $removeZerothIndex = $retVal;
    unset($removeZerothIndex[0]);

    foreach($removeZerothIndex as $value){
      $question_id = $retVal[1]->question_id;
      $question_type = $retVal[1]->question_type;
      $description = $retVal[1]->description;
      $potential_answers = $retVal[1]->potential_answers;
      $correct_answers = $retVal[1]->correct_answers;
      echo $question_id.'<br>'.$question_type.'<br>'.$description.'<br>'.$potential_answers.'<br>'.$correct_answers.'<br>';
    }

    if(array_key_exists(1, $retVal)) {
      $question_id = $retVal[1]->question_id;
      $question_type = $retVal[1]->question_type;
      $description = $retVal[1]->description;
      $potential_answers = $retVal[1]->potential_answers;
      $correct_answers = $retVal[1]->correct_answers;
      echo $question_id.'<br>'.$question_type.'<br>'.$description.'<br>'.$potential_answers.'<br>'.$correct_answers.'<br>';
    }
    var_dump($retVal);

    Example Return Value:
    [{"message":"Success SELECTING from teacher_account, owns_question, question","success":1},{"question_id":"1","question_type":"desc","description":"title","potential_answers":"potentialA","correct_answers":"correctA"}]

  */


  function searchActiveQuestionGivenActiveQuestionSet($page, $rowsPerPage, $question_session_id){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT question.question_id, question.teacher_id, question.question_type, question.description, question.potential_answers, question.correct_answers
            FROM question_session, question_set, active_question, question
            WHERE question_session.question_set_id = question_set.question_set_id
              AND question_set.question_set_id = active_question.question_set_id
              AND active_question.question_id = question.question_id
              AND question_session.id = :question_session_id
              ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':question_session_id', $question_session_id);

    try{
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
    $response["message"] = "Success SELECTING student_account, class_course_section, active_question_set";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>