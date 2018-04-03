<?php

  // This script is to be used in the form where the Teacher DELETES an existing question
  // It expects only a question_id.
  //     __________________
  //    |question          |
  //    |__________________|
  //    |question_id       |
  //    |------------------|
  //    |teacher_id        |
  //    |question_type     |
  //    |description       |
  //    |potential_answers |
  //    |correct_answers   |
  //    |__________________|

  include_once('Connection.php');
  include_once('C_Question.php');
  include_once('isIdExistFunctions.php');

  // get question_id from DB
  $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : null;
  error_log("Question ID at deleteQuestion: ".$question_id);
  if(is_null($question_id) || empty($question_id)){
      error_log("deleteQuestion FAIL");
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR UPDATING, question_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  if(isQuestionIdExist($question_id)){
    $question = new Question($question_id, '%', '%', '%', '%', '%');
    return $question->delete();

  }else{
    // question_id does not exist
    error_log("deleteQuestion FAIL, did not exist");
    $response = array();
    $response["message"] = "ERROR UPDATING, question_id: ".$question_id." does not exist";
    $response["success"] = 0;
    return json_encode($response);
  }
?>
