<?php
/*
* Adds a random, unique string to the database for email confirmation
* Include this and call the function, pass the connection
*/

    include('confirmationEmailTeacher.php');

    
    function addUniqueHash($connection, $email){
    
      $pdo = $connection->getConnection();
        
      $salt = rand();
      $hash = hash('whirlpool',  time() . $salt); //generates random unique ID with a random int and the time hashed with a 128 bit hashing function
        
        
      $sql = "UPDATE teacher_account SET uniqueID = '$hash' WHERE email = '$email'";
      $stmt = $pdo->prepare($sql);
      try{
        $stmt->execute();
      }
      catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR executing query $sql in registerConfirmationTeacher.php: ".$hash." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }
        
        $pdo = null;
        
        sendMessage($connection, $email); //send the email message
    }

    

?>