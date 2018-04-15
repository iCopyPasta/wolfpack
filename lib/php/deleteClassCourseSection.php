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
  include_once('C_ClassCourseSection.php');
  include_once('isIdExistFunctions.php');

  // get question_id from DB
  $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;
  if(is_null($class_id) || empty($class_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR DELETING, class_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  if(true){
      error_log($class_id." good");
    $class = new ClassCourseSection($class_id, '%', '%', '%', '%');
    return $class->delete();
  }else{
      error_log($class_id." bad");
    // question_id does not exist
    $response = array();
    $response["message"] = "ERROR DELETING, class_id: ".$class_id." does not exist";
    $response["success"] = 0;
    return json_encode($response);
  }
?>
