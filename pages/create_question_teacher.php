<?php

  /*
  This script is to be run from the page the teacher uses to create a question.

  Expectations from POST:
    question          -> a string
    potentialAnswers  -> an array of potential answers in JSON form
    correctAnswers    -> array of correct answer in JSON form

  //TODO: determine if "tags" or "asked" attributes need to be manipulated here; they are currently set to 'none' and 0 respetively
  */

  $alertString="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('/pages/Connection.php');
    include('/pages/C_Question.php');

    $questionBody = isset($_POST['question']) ? $_POST['question'] : null;
    $potentialAnswers = isset($_POST['potentialAnswers']) ? $_POST['potentialAnswers'] : null;
    $correctAnswers = isset($_POST['correctAnswers']) ? $_POST['correctAnswers'] : null;

    // ensure that $potentialAnswers is JSON encode-able array
    $potAnsJSON = json_encode($potentialAnswers);
    if(json_last_error_msg() != JSON_ERROR_NONE){
      // potentialAnswers is not correct JSON form
      $response = array();
      $response["message"] = "ERROR: potentialAnswers is not correct JSON form";
      $response["success"] = 0;
      echo json_encode($response);
//      exit();
    }

    // ensure that $correctAnswers is JSON encode-able array
    $corAnsJSON = json_encode($correctAnswers);
    if(json_last_error_msg() != JSON_ERROR_NONE){
      // correctAnswers is not correct JSON form
      $response = array();
      $response["message"] = "ERROR: correctAnswers is not correct JSON form";
      $response["success"] = 0;
      echo json_encode($response);
//      exit();
    }

    // ensure $questionBody, $potentialAnswers, and $correctAnswers are not null
    if(!empty($questionBody)){
      if(!empty($potentialAnswers)){
        if(!empty($correctAnswers)){
          // good things happen
          include_once('/pages/C_Question.php');
          $question = new Question('%', $questionBody,'none', 0, $potAnsJSON, $corAnsJSON);
          $retVal = $question->insert();
        }
        else{
          // corAnsJSON is empty
          $response = array();
          $response["message"] = "ERROR: correct answer JSON is empty";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }
      else{
        // potentialAnswers is empty
        $response = array();
        $response["message"] = "ERROR: potential answer JSON is empty";
        $response["success"] = 0;
        echo json_encode($response);
      }
    }
    else{
      // questionBody is empty
      $response = array();
      $response["message"] = "ERROR: question is empty";
      $response["success"] = 0;
      echo json_encode($response);
    }
  }
?>