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
function createQuestion($teacher_id, $first_name, $last_name, $question_type, $description, $potential_answers, $correct_answers){
  include_once('Connection.php');

  //check if teacher_id exists in teacher_account table
  if(!isTeacherIdExist($teacher_id)){
    // fail JSON response: teacher does not exist
    $response = array();
    $response["message"] = "ERROR creating question: teacher_id does not exist";
    $response["success"] = 0;
    return json_encode($response);
  }

  // insert new row into question table
  $question = new Question('%', $description, $question_type, $potential_answers, $correct_answers);
  $questionJSON = $question->insert();
  $decodedRetVal = json_decode($questionJSON);
  if($decodedRetVal->success != 1) {
    // failed to insert question
    return $questionJSON;
  }

  // get the id of the new row
  $question_id = $decodedRetVal->idInserted;

  // insert new row into owns_question table
  $ownsQuestion = new OwnsQuestion($question_id, $teacher_id);
  $retVal = $ownsQuestion->insert();
  $decodedRetVal = json_decode($retVal);
  if($decodedRetVal->success != 1) {
    // failed to insert owns_question
    return $questionJSON;
  }

  // success JSON response
  $response = array();
  $response["message"] = "Inserted into question and owns_question: "."question_id->".$question_id." "."teacher_id->".$teacher_id;
  $response["success"] = 1;
  return json_encode($response);
}

?>