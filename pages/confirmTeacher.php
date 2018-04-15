<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <script type = "text/javascript" src="createAccountValidator.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS
    <link href="../../../../dist/css/bootstrap.min.css" rel="stylesheet">-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <style>
    html,
body {
  height: 100%;
}
body {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-align: center;
  -ms-flex-pack: center;
  -webkit-box-align: center;
  align-items: center;
  -webkit-box-pack: center;
  justify-content: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}
.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .checkbox {
  font-weight: 400;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
  </head>

  <body class="text-center">

    <!--
    this php code will do the following:
    1. validate the two passwords match
      - if they don't match display an error and do not continue
    2. ensure the email does not already exist on the DB
      - if it already exists then do not change the DB
    3. create the account in the DB
   -->
   

    <?php include("../lib/php/header.php"); ?>
      
    <div class = "container" >
        <!--Insert pass/fail message here -->
        
         <?php
      include('../lib/php/Connection.php');
      $uniqueID=$_GET["uniqueid"]; //save the unique id from the url
      
      $connection = new Connection;
      $pdo = $connection->getConnection();
        
    
      $sql = "SELECT email FROM teacher_account WHERE uniqueID = '$uniqueID'";
      $stmt = $pdo->prepare($sql);
      try{
        $stmt->execute();
      }
      catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR executing query $sql in confirmTeacher.php: ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }
        
        
      $result = $stmt->fetchAll();
      
      if (empty($result)) {
          //set container to failure message
          echo "<H1> Invalid Account Code. Account confirmation failure.";
      }
      else {
          //set container to confirmed message and change database to confirmed
          
      $sql = "UPDATE teacher_account SET is_confirmed = 1 WHERE uniqueID = '$uniqueID'";
      $stmt = $pdo->prepare($sql);
      try{
        $stmt->execute();
      }
      catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR executing query $sql in confirm.php: ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }
          echo "<H1> Account confirmed successfully. Thank you.";
      }
      
        
        $pdo = null;
    ?>
        
        
     </div>
  </body>
</html>