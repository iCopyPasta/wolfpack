<?php

  // This script is to be used in the form where the Teacher CHANGES an existing question
  // It expects only a question_id.
  // Fields in the form may be left blank. If that is the case, the new value will be the old value.
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

  // get question_id from DB
  $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : null;
  if(is_null($question_id) || empty($question_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR UPDATING, question_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  $question = new Question($question_id, '%', '%', '%', '%', '%');
  $retVal = json_decode($question->select());

  $old_teacher_id = $retVal[1]->teacher_id;
  $old_question_type = $retVal[1]->question_type;
  $old_description = $retVal[1]->description;
  $old_potential_answers = $retVal[1]->potential_answers;
  $old_correct_answers = $retVal[1]->correct_answers;

  // get values from form
  $new_teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : $old_teacher_id;
  $new_question_type = isset($_POST['question_type']) ? $_POST['question_type'] : $old_question_type;
  $new_description = isset($_POST['description']) ? $_POST['description'] : $old_description;
  $new_potential_answers = isset($_POST['potential_answers']) ? $_POST['potential_answers'] : $old_potential_answers;
  $new_correct_answers = isset($_POST['correct_answers']) ? $_POST['correct_answers'] : $old_correct_answers;

  // if the form field is blank, the new value is the old value
  if(empty($new_teacher_id)) $new_teacher_id = $old_teacher_id;
  if(empty($new_question_type)) $new_question_type = $old_question_type;
  if(empty($new_description)) $new_description = $old_description;
  if(empty($new_potential_answers)) $new_potential_answers = $old_potential_answers;
  if(empty($new_correct_answers)) $new_correct_answers = $old_correct_answers;

  return $question->update($new_teacher_id, $new_description, $new_question_type, $new_potential_answers, $new_correct_answers);
?>
