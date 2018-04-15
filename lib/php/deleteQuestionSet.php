<?php

  // This script is to be used in the form where the Teacher DELETES an existing question_set
  // It expects only a question_set_id.
//  __________________
//  |question_set      |
//  |__________________|
//  |question_set_id   |
//  |------------------|
//  |question_set_name |
//  |teacher_id        |
//  |__________________|

  include_once('Connection.php');
  include_once('C_QuestionSet.php');
  include_once('isIdExistFunctions.php');

  // get question_id from DB
  $question_set_id = isset($_POST['question_set_id']) ? $_POST['question_set_id'] : null;
  if(is_null($question_set_id) || empty($question_set_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR DELETING, question_set_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  if(isQuestionSetIdExist($question_set_id)){
    $question_set = new QuestionSet($question_set_id, '%', '%');
    return $question_set->delete();
  }else{
    // question_id does not exist
    $response = array();
    $response["message"] = "ERROR DELETING, question_set_id: ".$question_set_id." does not exist";
    $response["success"] = 0;
    return json_encode($response);
  }
?>
