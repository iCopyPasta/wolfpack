<?php
//Sets an active/inactive question set based on post variables
   session_start();

    include_once('Connection.php');
    include_once('C_ActiveQuestionSet.php');
    $connection = new Connection;

    $class_id = $_POST["class_id"];
    $question_set_id = $_POST["question_set_id"];
    $activity = $_POST["activity"];
   
    $ActiveQuestionSetObject = new ActiveQuestionSet($class_id,$question_set_id);

    if ($activity == 0) {
        $ActiveQuestionSetObject->delete();
    }
    if ($activity == 1) {
        $ActiveQuestionSetObject->insert();
    }


?>