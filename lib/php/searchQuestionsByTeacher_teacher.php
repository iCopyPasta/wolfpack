<?php
  /*
    //TODO: documentation and usage
    function searchQuestionsByTeacher
    arguments:  page - the current page
                rowsPerPage - rows per page
                teacher_id - can use '%' wildcard if unknown
                first_name - teacher first name
                last_name - teacher last name
    return: JSON obj containing question_id, question_type, description, JSON potential_answers, and JSON correct_answers

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
  function searchQuestionsByTeacher($page, $rowsPerPage, $teacher_id, $first_name, $last_name){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT question.question_id, question.question_type, question.description, question.potential_answers, question.correct_answers
                FROM teacher_account, owns_question, question
                WHERE teacher_account.teacher_id = owns_question.teacher_id
                  AND owns_question.question_id = question.question_id
                  AND teacher_account.teacher_id LIKE :teacher_id
                  AND teacher_account.first_name LIKE :first_name
                  AND teacher_account.last_name LIKE :last_name";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':teacher_id', $teacher_id);
    $stmt->bindValue(':first_name', $first_name);
    $stmt->bindValue(':last_name', $last_name);

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
    $response["message"] = "Success SELECTING from teacher_account, owns_question, question";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>