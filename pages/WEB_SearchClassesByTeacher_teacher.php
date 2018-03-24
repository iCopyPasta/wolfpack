<?php

  /*
  This script is to be run from the page the teacher uses to create a course.

  Expectations from POST:
    title       -> a string
    description -> an array of potential answers in JSON form


  */

  $alertString="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('searchClassesByTeacher_teacher.php');

//    $page = isset($_POST['page']) ? $_POST['page'] : null;
//    $rowsPerPage = isset($_POST['rowsPerPage']) ? $_POST['rowsPerPage'] : null;
//    $teacherFName = isset($_POST['teacherFName']) ? $_POST['teacherFName'] : null;
//    $teacherLName = isset($_POST['teacherLName']) ? $_POST['teacherLName'] : null;
    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $teacher_id = isset($_POST['teacher_id']) ? $_POST['teacher_id'] : null;

    if(empty($title)){
      // $title is empty
      $response = array();
      $response["message"] = "ERROR: title is empty";
      $response["success"] = 0;
//      echo json_encode($response);
      return $response;
    }

    if(empty($teacher_id)){
      // $teacher_id is empty
      $response = array();
      $response["message"] = "ERROR: teacher_id is empty";
      $response["success"] = 0;
//      echo json_encode($response);
      return $response;
    }

    $jsonResponse = searchClassTitleSectionByTeacher('%', '%', $title, $teacher_id);
    return json_decode($jsonResponse);


  }
?>