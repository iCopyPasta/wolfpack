<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

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
    <!--
    this php code will do the following:
    1. Validate if the user account/password is valid
    2. if valid, redirect page to student landing page
        if not valid, alert the user that their attempt was invalid
   -->
  <?php
      $alertString="";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $insertEmail=$_POST["inputEmail"];
        $insertPass=$_POST["inputPassword"];

        include('C_Sign_In_Class_Student.php');
        $studentSignIn = new StudentSignIn($insertEmail, $insertPass);

        // true if email+pw found; false if no record found
        if(json_decode($studentSignIn->select())->success){
          $alertString="";
          header("Location: logged_in_student.php");  //if the query was successful
          die();
        }
        // validate password match
        $host='localhost';
          $user='root';
          $pass='';
          $db='iClicker';
          $con=mysqli_connect($host, $user, $pass, $db);
          if($con) echo 'connected successfully to testdb database<br>';
          $insertEmail=$_POST["inputEmail"];
          $insertPass=$_POST["inputPassword"];
          $sql="SELECT email, salted_password FROM student_account WHERE email =
               '$insertEmail' AND salted_password = 
               SHA('$insertPass')";
          echo $sql;
          $query=mysqli_query($con,$sql);
          
          if(mysqli_num_rows($query)!=0) { //if the query has any results
                        $alertString="";
                        header("Location: logged_in_student.php");  //if the query was successful
                        die();
          }
        // passwords don't match
        else{
          $alertString='<div class="alert alert-danger">
                <strong>Error.</strong> Username or password does not exist.
                </div>';
        }
      }
    ?>

  <body class="text-center">

<?php include("../lib/php/header.php"); ?>
<?php include("../lib/php/header.php"); ?>
  
    <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" required>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
        <!-- warn user if login was incorrect -->
                    <?php
                      echo $alertString;
                    ?>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">Credit: Twitter bootstrap 4.0 documentation. Working demo for 2-13-18.</p>
    </form>


  </body>
</html>
