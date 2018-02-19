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
    <?php
      function passwordMatch($pw1, $pw2){
        return ($pw1 == $pw2);
      }
      $matchPWString="";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // validate password match
        if(passwordMatch($_POST["inputPassword"], $_POST["inputConfirmPassword"])){
          $matchPWString="";
          // TODO: ensure email does not already exist
          // create account
          $host='localhost';
          $user='root';
          $pass='';
          $db='iClicker';
          $con=mysqli_connect($host, $user, $pass, $db);
          if($con) echo 'connected successfully to testdb database<br>';
          $insertEmail=$_POST["inputEmail"];
          $insertPass=$_POST["inputPassword"];
          $sql="insert into student_account (email, salted_password) values ("
               . "'" . $insertEmail . "',"
               . "SHA('" . $insertPass . "'))";
          $query=mysqli_query($con,$sql);
          if($query) echo 'data inserted successfully<br>';
        }
        // passwords don't match
        else{
          $matchPWString='<div class="alert alert-danger">
                <strong>Error.</strong> Passwords don\'t match.
                </div>';
        }
      }
    ?>

    <?php include("includes/header.php"); ?>
      
    <div class = "container" >
         <!-- <form class="form-signin" action="createAccount.php" method="post"> -->
         <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
               <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
               <h1 class="h3 mb-3 font-weight-normal">Sign up</h1>

               <label for="inputEmail" class="sr-only">Email address</label>
               <input type="email" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>

               <label for="inputPassword" class="sr-only">Password</label>
               <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" required>

               <div class="checkbox mb-3">
                    <br>

                    <label for="inputPassword" class="sr-only">Repeat_Password</label>
                    <input type="password" name="inputConfirmPassword" id="inputConfirmPassword" class="form-control" placeholder="Repeat Password" required>

                    <!-- warn user if passwords don't match -->
                    <?php
                      echo $matchPWString;
                    ?>


                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                    <!-- TODO: update the following line-->
                    <p class="mt-5 mb-3 text-muted">Credit: Twitter bootstrap 4.0 documentation. Working demo for 2-13-18.</p>
               </div>
          </form>
          <hr>
     </div>
  </body>
</html>