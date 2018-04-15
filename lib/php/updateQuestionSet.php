<?php

  // This script is to be used in the form where the Teacher CHANGES an existing question_set
  // It expects only a question_set_id.
  // Fields in the form may be left blank. If that is the case, the new value will be the old value.
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

  // get question_set_id from DB
  $question_set_id = isset($_POST['question_set_id']) ? $_POST['question_set_id'] : null;
  if(is_null($question_set_id) || empty($question_set_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR UPDATING, question_set_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  $question_set = new QuestionSet($question_set_id, '%', '%');
  $retVal = json_decode($question_set->select());

  $old_teacher_id = $retVal[1]->teacher_id;
  $old_question_set_name = $retVal[1]->question_set_name;

  // get values from form
  $new_teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : $old_teacher_id;
  $new_question_set_name = isset($_POST['question_set_name']) ? $_POST['question_set_name'] : $old_question_set_name;

  // if the form field is blank, the new value is the old value
  if(empty($new_teacher_id)) $new_teacher_id = $old_teacher_id;
  if(empty($new_question_set_name)) $new_question_set_name = $old_question_set_name;

  return $question_set->update($new_teacher_id, $new_question_set_name);
?>
