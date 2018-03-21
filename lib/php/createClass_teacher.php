<?php
/*
  function searchQuestionsByTeacher
  arguments:  teacher_id  - teacher id number
              first_name  - placeholder; not currently used in SQL
              last_name   - placeholder; not currently used in SQL
              title       - class_course_section title
              description - class_course_section description
              offering    - class_course_section offering
              location    - class_course_section location

  return: JSON obj containing associative array.  See "Example Return Value" below.
          message - string
          success - boolean: 1 = success, 0 = failure

  Example Usage:
  $response = createClass('1', 'Sukmoon', 'Chang', 'CMPSC 460', 'This is a description', 'offering', 'Olmstead 212');
  $isSuccesful = $retVal->success;
  if($isSuccesful){
    // do something when insert is successful
    echo 'class successfully created';
  }else{
    // do something when insert fails
    echo 'failed to create class: '.$retVal->message;
  }

  Example Return Value:
  {"message":"Inserted into class_course_section and teaches: class_id->4 teacher_id->1","success":1}

*/
include('isIdExistFunctions.php');

function createClass($teacher_id, $first_name, $last_name, $title, $description, $offering, $location){
  include_once('Connection.php');
  include_once('C_ClassCourseSection.php');
  include_once('C_Teaches.php');

  //check if teacher_id exists in teacher_account table
  if(!isTeacherIdExist($teacher_id)){
    // fail JSON response: teacher does not exist
    $response = array();
    $response["message"] = "ERROR creating question: teacher_id ". $teacher_id." does not exist";
    $response["success"] = 0;
    return json_encode($response);
  }

  // insert new row into class_course_section table
  $classCourseSection = new ClassCourseSection('%', $title, $description, $offering, $location);
  $questionJSON = $classCourseSection->insert();
  $decodedRetVal = json_decode($questionJSON);
  if($decodedRetVal->success != 1) {
    // failed to insert question
    return $questionJSON;
  }

  // get the id of the new row
  $classCourseSectionId = $decodedRetVal->idInserted;

  // insert new row into owns_question table
  $teaches = new Teaches($teacher_id, $classCourseSectionId);
  $retVal = $teaches->insert();
  $decodedRetVal = json_decode($retVal);
  if($decodedRetVal->success != 1) {
    // failed to insert owns_question
    return $questionJSON;
  }

  // success JSON response
  $response = array();
  $response["message"] = "Inserted into class_course_section and teaches: "."class_id->".$classCourseSectionId." "."teacher_id->".$teacher_id;
  $response["success"] = 1;
  return json_encode($response);
}

?>