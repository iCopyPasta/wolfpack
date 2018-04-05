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
  if(is_null($session_id) || empty($session_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR, session_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  //ensure $question_history_id is populated
  $question_history_id = isset($_POST['question_history_id']) ? $_POST['question_history_id'] : null;
  if(is_null($question_history_id) || empty($question_history_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR, question_history_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  //find live statistics
  $question = new Answers('%', $session_id, $question_history_id, '%', '%', '%');
  $jsonVal = json_decode($question->select());
  unset($jsonVal[0]); // remove response array
  foreach ($jsonVal as $key => $value) {
    $answerArray = (array)$value;  // cast object to associative array
    $answer = json_decode($answerArray['answer']);  // get value of 'answer' element of associative array

    //step through $answer and increment $retValArray where $answer value == $retValArray index
    foreach ($answer as $key2 => $value2) {
      $retVal[$value2] = isset($retVal[$value2]) ? $retVal[$value2] + 1 : 0;
    }
  }

  // sort array by keys; so keys are in order of {0th, 1st, 2nd, 3rd} instead of {1st, 3rd, 2nd, 0th}
  ksort($retVal);

  // fill in the gaps in the array
  for ($i = 0; $i < sizeof($retVal); $i++) {
    if (!isset($retVal[$i])) {
      $retVal[$i] = 0;
    }
  }

  // sort array by keys; so keys are in order of {0th, 1st, 2nd, 3rd} instead of {1st, 3rd, 2nd, 0th}
  ksort($retVal);

  // success JSON response
  $response = array();
  $response["message"] = "Success calculating live statistics";
  $response["success"] = 1;
  $retVal = array($retVal);
  array_unshift($retVal, $response);

  return $retVal;

?>