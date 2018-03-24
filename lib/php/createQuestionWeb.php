<?php
//Creates a class using POST variables
   session_start();

    include_once('Connection.php');
    include_once('C_Question.php');
    $connection = new Connection;

    $question_type = $_POST["question_type"];
    $description = $_POST["description"];
    $possible_answers = $_POST["possible_answers"];
    $correct_answers = $_POST["correct_answers"];
   
    $questionObject = new Question("%",$_SESSION['id'],$description,$question_type,$possible_answers,$correct_answers);
    $questionObject->insert();
    

    header("Location: ..\..\pages\manage_questions.php");

?>