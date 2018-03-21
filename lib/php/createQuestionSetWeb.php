<?php
//Creates a class using POST variables
   session_start();

    include_once('Connection.php');
    include_once('C_QuestionSet.php');
    include_once('C_QuestionIsIn.php');
    $connection = new Connection;

    $question_set_name = $_POST["question_set_name"];
    $questions = $_POST["questions"];


    $questionSetObject = new QuestionSet("%",$_SESSION['id'],$question_set_name);
    $questionSetObject->insert();

    $questionSet = json_decode($questionSetObject->select(),true);

    $questionSetId = $questionSet[1]['question_set_id'];
    
    //error_log($questions);

    foreach (json_decode($questions,true) as $value) {
        
        $QuestionIsInObject = new QuestionIsIn($questionSetId,$value);
        $QuestionIsInObject->insert();
        
    }

    header("Location: ..\..\index.php");
    echo "Question set added successfully.";

?>