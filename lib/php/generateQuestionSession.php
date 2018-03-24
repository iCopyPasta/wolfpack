<?php
//Sets an active/inactive question set based on post variables
   session_start();


    include_once('Connection.php');


    $connection = new Connection;

    $pdo = $connection->getConnection();

    $class_id = $_POST["class_id"];
    $question_set_id = $_POST["question_set_id"];


    $sql = "INSERT INTO question_session (class_id,question_set_id) VALUES ($class_id,$question_set_id)";
    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response

        echo $e->getMessage();
        
      }

    $last_id = $pdo->lastInsertId();
    
    echo $last_id; //echos the id of the inserted question session back to the ajax
    
    //error_log("Returning question_session_id: ".$last_id);
    
    

?>