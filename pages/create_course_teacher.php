<?php

  /*
  This script is to be run from the page the teacher uses to create a course.

  Expectations from POST:
    title       -> a string
    description -> an array of potential answers in JSON form


  */

  $alertString="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('/pages/Connection.php');
    include('/pages/C_ClassCourse.php');

    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;

    // ensure $title and $description are not empty
    if(!empty($title)){
      if(!empty($description)){
        // good things happen here!
        include_once('C_ClassCourse.php');
        $class = new ClassCourse('%', $title, $description);
      }
      else{
        // $description is empty
        $response = array();
        $response["message"] = "ERROR: description answer JSON is empty";
        $response["success"] = 0;
        echo json_encode($response);
      }
    }
    else{
      // $title is empty
      $response = array();
      $response["message"] = "ERROR: title is empty";
      $response["success"] = 0;
      echo json_encode($response);
    }
  }
?>