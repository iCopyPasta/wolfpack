<?php
  /*
    function searchActiveQuestionGivenActiveQuestionSet
    arguments:  page - the current page
                rowsPerPage - rows per page
                question_session_id
    return: JSON obj containing current active question

    Example Usage:
    $retVal = searchActiveQuestionGivenActiveQuestionSet('1', '1', '1');

    Example Return Value:
    [{"message":"Success SELECTING student_account, class_course_section, active_question_set","success":1},{"question_id":"1","teacher_id":"0","question_type":"selection","description":"Itaque ex aliquid laudantium rerum error earum quibusdam voluptatum enim consequatur adipisci minus natus quis vel.","potential_answers":"none","correct_answers":"none"}]

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