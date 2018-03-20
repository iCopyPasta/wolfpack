<?php
  /*
  * Generic class to allow logins.
  */
  class Connection{

    function getConnection(){

      $host = 'localhost';
      $username = 'alpha_wolfpack';
      $password = 'cashRule$Everything101';
      $dbname = 'wolfpack_tester';

      try{
        $conn    = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;

      }catch (PDOException $e){
        //may wish to port output elsewhere
        $response = array();
        $response["message"] = "ERROR CONNECTION FAILURE: " . $e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
      }

    }


    function getConnectionArgs($hostArg, $usernameArg, $passwordArg, $dbnameArg){

      if($hostArg !== null){
        $host = $hostArg;
      }

      if($usernameArg !== null){
        $username = $usernameArg;
      }

      if($passwordArg !== null){
        $password = $passwordArg;
      }

      if($dbnameArg !== null){
        $dbname = $dbnameArg;
      }


      try{
        $conn    = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;

      }catch (PDOException $e){
        //may wish to port output elsewhere
        $response = array();
        $response["message"] = "ERROR CONNECTION FAILURE: " . $e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
      }
    }
  }
?>
