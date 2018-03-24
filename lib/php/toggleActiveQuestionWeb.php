<?php
//Sets an active/inactive question set based on post variables
   session_start();

    include_once('Connection.php');
    include_once('C_ActiveQuestion.php');
    $connection = new Connection;

    
    $question_set_id = $_POST["question_set_id"];
    $question_id = $_POST["question_id"];
    $activity = $_POST["activity"];
    $question_history_id = $_POST["question_history_id"];
   
    $ActiveQuestionObject = new ActiveQuestion($question_set_id,$question_id,$question_history_id);

    if ($activity == 0) {
        $ActiveQuestionObject->delete();
    }
    if ($activity == 1) {
        $ActiveQuestionObject->insert();
    }


?>