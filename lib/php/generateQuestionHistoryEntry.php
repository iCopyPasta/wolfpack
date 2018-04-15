<?php
//Sets an active/inactive question set based on post variables
   session_start();


    include_once('Connection.php');


    $connection = new Connection;

    $pdo = $connection->getConnection();
    
    $session_id= $_POST["session_id"];
    $question_id = $_POST["question_id"];


    $sql = "INSERT INTO question_history (session_id,question_id) VALUES ($session_id,$question_id)";
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