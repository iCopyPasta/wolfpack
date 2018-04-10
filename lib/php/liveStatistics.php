<?php
  /*
  Arguments:
  expects _Post to contain a 'session_id' and 'question_history_id'

  Return:
  JSON
    array (size=2)
      0 =>
        array (size=2)
          'message' => string 'Success calculating live statistics' (length=35)
          'success' => int 1
      1 =>
        array (size=4)
          0 => int 562
          1 => int 548
          2 => int 0
          3 => int 567
   */

  include_once('Connection.php');
  include_once('C_Answers.php');

  //ensure $session_id is populated
  $session_id = isset($_POST['session_id']) ? $_POST['session_id'] : null;
  if (is_null($session_id) || empty($session_id)) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR, session_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  //ensure $question_history_id is populated
  $question_history_id = isset($_POST['question_history_id']) ? $_POST['question_history_id'] : null;
  if (is_null($question_history_id) || empty($question_history_id)) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR, question_history_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  //get the count of potential_question field from question table
  $sql = "SELECT DISTINCT potential_answers
                FROM question, question_history, answers
                WHERE answers.question_history_id = question_history.id
                  AND question_history.question_id = question.question_id
                  AND answers.question_history_id = :question_history_id
          ";

  $connection = new Connection;
  $pdo = $connection->getConnection();
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':question_history_id', $question_history_id);

  try {
    $stmt->execute();
  } catch (Exception $e) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR SELECTING: " . $e->getMessage();
    $response["success"] = 0;
    return json_encode($response);
  }

  // success selecting potential_answers
  $selectPotentialAnswers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // get the count of potential_answers
  if (empty($selectPotentialAnswers) ) {
      $countPotentialAnswers = 0;
      error_log("setting countPotentialAnswers to 0, no answers found.");
  }
  else
      $countPotentialAnswers = count(json_decode($selectPotentialAnswers[0]['potential_answers']));

  // fail if count == 0; there are no potential_answers
  if($countPotentialAnswers == 0) {
    $response = array();
    $response["message"] = "Success calculating live statistics";
    $response["success"] = 1;
    
    $retVal = "No submissions detected.";
    $retVal = array($retVal);
    array_unshift($retVal, $response);
  
    echo json_encode($retVal);
    return json_encode($retVal);
  }

  //initialize retVal array to 0
  for($i = 0; $i < $countPotentialAnswers; $i++){
    $retVal[$i] = 0;
  }

  //find live statistics
  $answers = new Answers('%', $session_id, $question_history_id, '%', '%', '%');
  $jsonVal = json_decode($answers->select());
  unset($jsonVal[0]); // remove response array
  foreach ($jsonVal as $key => $value) {
    $answerArray = (array)$value;  // cast object to associative array
    $answer = json_decode($answerArray['answer']);  // get value of 'answer' element of associative array

    //step through $answer and increment $retValArray where $answer value == $retValArray index
    foreach ($answer as $key2 => $value2) {
      $retVal[$value2] = isset($retVal[$value2]) ? $retVal[$value2] + 1 : 0;
    }
  }

  // success JSON response
  $response = array();
  $response["message"] = "Success calculating live statistics";
  $response["success"] = 1;
  $retVal = array($retVal);
  array_unshift($retVal, $response);

  echo $retVal;
  return $retVal;

?>