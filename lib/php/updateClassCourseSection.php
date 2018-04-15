<?php

  // This script is to be used in the form where the Teacher CHANGES an existing class_course_section
  // It expects only a class_id.
  // Fields in the form may be left blank. If that is the case, the new value will be the old value.
//   _____________________
//  |class_course_section |
//  |_____________________|
//  |class_id             |
//  |---------------------|
//  |title                |
//  |description          |
//  |offering             |
//  |location             |
//  |_____________________|

  include_once('Connection.php');
  include_once('C_ClassCourseSection.php');
  
  error_log("Reached updateClassCourseSection");

  // get class_id from DB
  $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;
  if(is_null($class_id) || empty($class_id)){
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR UPDATING, class_id cannot be null or empty";
    $response["success"] = 0;
    return json_encode($response);
  }

  $class = new ClassCourseSection($class_id, '%', '%', '%', '%');
  $retVal = json_decode($class->select());
  
  error_log($class_id);
  error_log(json_encode($retVal));

  $old_title = $retVal[1]->title;
  $old_description = $retVal[1]->description;
  $old_offering = $retVal[1]->offering;
  $old_location = $retVal[1]->location;

  // get values from form
  $new_title = isset($_POST['title']) ? $_POST['title'] : $old_title;
  $new_description = isset($_POST['description']) ? $_POST['description'] : $old_description;
  $new_offering = isset($_POST['offering']) ? $_POST['offering'] : $old_offering;
  $new_location = isset($_POST['location']) ? $_POST['location'] : $old_location;

  // if the form field is blank, the new value is the old value
  if(empty($new_title)) $new_title = $old_title;
  if(empty($new_description)) $new_description = $old_description;
  if(empty($new_offering)) $new_offering = $old_offering;
  if(empty($new_location)) $new_location = $old_location;

  $class->update($new_title, $new_description, $new_offering, $new_location);
  header("Location: ..\..\pages\manage_class.php");
?>
