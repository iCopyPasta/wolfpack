

<?php
  /*
    function searchQuestionsMadeByTeacher
    arguments:  professor_id - professor's id number
    return: JSON obj containing a list of questions the teacher made

  //TODO: the following comments
    Example Usage:

    include_once('/pages/searchQuestionsMadeByTeacher.php');
    $search = searchQuestionsMadeByTeacher(1);

    Example Return Value:
    [
      {"message":"Success SELECTING from question, owns, professor_account","success":1},
      {"question_id":"1","description":"1 Whats your full name?","tags":"someTag","asked":"0","potential_answers":"[\"Yes\", \"No\", \"Maybe\", \"42\"]","correct_answers":"[0, 3]"},
      {"question_id":"21","description":"21 Can you juggle?","tags":"someTag","asked":"0","potential_answers":"[\"Yes\", \"No\", \"Maybe\", \"42\"]","correct_answers":"[0, 3]"},
      {"question_id":"41","description":"41 Are you a good cleaner?","tags":"someTag","asked":"0","potential_answers":"[\"Yes\", \"No\", \"Maybe\", \"42\"]","correct_answers":"[0, 3]"}
    ]

  */
  function searchQuestionsMadeByTeacher($professor_id){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT question.question_id, question.description, question.tags, question.asked, question.potential_answers, question.correct_answers
            FROM question, owns, professor_account
            WHERE professor_account.professor_id = owns.professor_id
              AND owns.question_id = question.question_id
              AND professor_account.professor_id = :professor_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':professor_id', $professor_id);

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
    $response["message"] = "Success SELECTING from question, owns, professor_account";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>