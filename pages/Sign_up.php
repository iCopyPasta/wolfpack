<?php
  function passwordMatch($pw1, $pw2){
    return ($pw1 == $pw2);
  }
  include_once('C_StudentAccount.php');
  include_once('Connection.php');
  $connection = new Connection;
  
  $insertEmail = (isset($_POST['inputEmail']) ? $_POST['inputEmail'] : null);
  $insertPass = (isset($_POST['inputPassword']) ? $_POST['inputPassword'] : null);
  $insertPass2 = (isset($_POST['inputConfirmPassword']) ? $_POST['inputConfirmPassword'] : null);
  $android = isset($_POST["android"]) ? $_POST["android"] : false;
  $matchPWString = '';
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // email,pw1,pw2 are all not null
    if(!(is_null($insertEmail) && is_null($insertPass) && is_null($insertPass2))) {
      //email does not already exist
      $selectStudentAccount = new StudentAccount('%','%','%','%', '%', $insertEmail);
      $qJSON = json_decode($selectStudentAccount->select(), true);
      $emailExist = isset($qJSON[1]['email']) ? $qJSON[1]['email'] : null;
      if(is_null($emailExist)) {
        //pw1 == pw2
        if(passwordMatch($insertPass, $insertPass2)) {
         
          //good things happen here!
          $options = ['cost' => 11];
          $hashPassword = password_hash($insertPass, PASSWORD_BCRYPT, $options);
          $selectStudentAccount = new StudentAccount('thisValueIsIgnored', 'firstname', 'lastname', $hashPassword, 'aValue', $insertEmail);
          echo $selectStudentAccount->insert(); // insert method returns a json_encoded response
          include('registerConfirmation.php');
          addUniqueHash($connection,$insertEmail); //sets UniqueID and confirmed vars in db, custom function -TR
          if(boolval($android)){
            exit(0);
          }
            
          
        } //pw1 != pw2
        else {
          //android pw1 != pw2
          if(boolval($android)){
            $response = array();
            $response["message"] = "ERROR: password doesn't match confirmPassword";
            $response["success"] = 0;
            echo json_encode($response);
            exit(0);
          }
          //web pw1 != pw2
          else{
            $response = array();
            $response["message"] = "ERROR: password doesn't match confirmPassword";
            $response["success"] = 0;
            echo json_encode($response);
          }
        }
      }
      //email already exists
      else {
        //android email already exists
        if(boolval($android)){
          $response = array();
          $response["message"] = "ERROR: email already exists";
          $response["success"] = 0;
          echo json_encode($response);
          exit(0);
        }
        //web email already exists
        else{
          $response = array();
          $response["message"] = "ERROR: email already exists";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }
    } // email,pw1,pw2 null
    else {
      //android email,pw1,pw2 null
      if(boolval($android)){
        $response = array();
        $response["message"] = "ERROR: email and pw cannot be null";
        $response["success"] = 0;
        echo json_encode($response);
        exit(0);
      }
      //web email,pw1,pw2 null
      else{
        $response = array();
        $response["message"] = "ERROR: email and pw cannot be null";
        $response["success"] = 0;
        echo json_encode($response);
        $matchPWString = '<div class="alert alert-danger">
                        <strong>Error: </strong> Email and pw cannot be null
                        </div>';
      }
    }
  }
?>

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