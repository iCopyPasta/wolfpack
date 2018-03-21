<?php
/*
* Adds a random, unique string to the database for email confirmation
* Include this and call the function, pass the connection
*/

    

    
    function sendMessage($connection, $email){
    
      $pdo = $connection->getConnection();
        
    
      $sql = "SELECT email,uniqueID FROM student_account WHERE email = '$email'";
      $stmt = $pdo->prepare($sql);
      try{
        $stmt->execute();
      }
      catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR executing query $sql in confirmationEmail.php: ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }
        
        $pdo = null;
        
        
        $result = $stmt->fetchAll(); //loads in result
    
        $theEmail = $result[0]['email'];
        $theID = $result[0]['uniqueID'];  //setting variables because array notation does not work properly in strings
    
    $to = "$theEmail";
    $subject = "Email Confirmation request from Pollato";
    
    
        
    $txt = "Please visit the following link to confirm your account: \n \n".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/confirm.php?uniqueid=$theID";
    $headers = "From: mailer@wolfpack.cs.hbg.psu.edu" . "\r\n";

    mail($to,$subject,$txt,$headers);
    
       
    }

    

?>