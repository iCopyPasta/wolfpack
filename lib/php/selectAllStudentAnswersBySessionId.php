<?php
  /*
  Arguments:
  expects _Post to contain a 'session_id'

  Return:
  JSON
    array (size=507)
  0 =>
    array (size=2)
      'message' => string 'Success selecting all answers for all students by session_id' (length=60)
      'success' => int 1
  1 =>
    array (size=2)
      'student_id' => string '723' (length=3)
      'answer' => string '["1"]' (length=5)
  2 =>
    array (size=2)
      'student_id' => string '890' (length=3)
      'answer' => string '["1","3"]' (length=9)
  3 =>
    array (size=2)
      'student_id' => string '486' (length=3)
      'answer' => string '["1"]' (length=5)
  4 =>
    array (size=2)
      'student_id' => string '687' (length=3)
      'answer' => string '["1","3"]' (length=9)
  5 =>
    array (size=2)
      'student_id' => string '782' (length=3)
      'answer' => string '["1"]' (length=5)
  6 =>
    array (size=2)
      'student_id' => string '753' (length=3)
      'answer' => string '["0","1","3"]' (length=13)
   */

  include_once('Connection.php');

  //ensure $session_id is populated
  $question_session_id = isset($_POST['session_id']) ? $_POST['session_id'] : null;
  if (is_null($question_session_id) || empty($question_session_id)) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR, session_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  //get the count of potential_question field from question table
  $sql = "SELECT student_account.student_id, answers.answer
                FROM student_account, answers, question_session
                WHERE student_account.student_id = answers.student_id
                  AND answers.session_id = question_session.id
                  AND question_session.id = :question_session_id
                ";

  $connection = new Connection;
  $pdo = $connection->getConnection();
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':question_session_id', $question_session_id);

  try {
    $stmt->execute();
  } catch (Exception $e) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR SELECTING: " . $e->getMessage();
    $response["success"] = 0;
    return json_encode($response);
  }

  // success selecting answers
  $selectAllStudentAnswers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // success JSON response
  $response = array();
  $response["message"] = "Success selecting all answers for all students by session_id";
  $response["success"] = 1;
  array_unshift($selectAllStudentAnswers, $response);
  return json_encode($retVal);

?>
