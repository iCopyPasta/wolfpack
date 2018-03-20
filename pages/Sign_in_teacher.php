 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

<?php
  $alertString = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // get input email and password
    $insertEmail = isset($_POST["inputEmail"]) ? $_POST["inputEmail"] : null;
    $insertPass = isset($_POST["inputPassword"]) ? $_POST["inputPassword"] : null;
    // boolean $android -> indicates android(true) or web(false); HTML can ignore this, will result in "false"
    $android = isset($_POST["android"]) ? $_POST["android"] : false;

    // search for email in db
    include('C_TeacherAccount.php');
    $selectTeacherAccount = new TeacherAccount('%','%','%','%', $insertEmail,'%','%','%');
    $qJSON = json_decode($selectTeacherAccount->select(), true);

    // email found
    if($qJSON[0]['success'] == 1){
      // get hashed password from search results
      // hashed password if email found
      // null if no email found
      $hashPW = isset($qJSON[1]['salted_password']) ? $qJSON[1]['salted_password'] : null;

      // compare user entered password with hashed pw from db
      // correct password and email found
      if(password_verify($insertPass, $hashPW) && !is_null($hashPW)) {
        //android login success
        if(boolval($android)){
          $response = array();
          $response["message"] = "Success(android): email + password found";
          $response["success"] = 1;
          echo json_encode($response);
          exit(0);
        }
        //web login success
        else{
          $response = array();
          $response["message"] = "Success: email + password found";
          $response["success"] = 1;
          echo json_encode($response);
          $alertString = "";
          $_SESSION['user'] = $insertEmail; //saves a session variable, unaccessable to the client, identifying them
          $_SESSION['accountType'] = "teacher"; //Keeps track of the type of account
          header("Location: logged_in_teacher.php");
        }
      }
      // email not found
      elseif(is_null($hashPW)){
        if(boolval($android)){
          $response = array();
          $response["message"] = "ERROR: incorrect email";
          $response["success"] = 0;
          echo json_encode($response);
          exit(0);
        }
        else{
          $response = array();
          $response["message"] = "ERROR: incorrect email";
          $response["success"] = 0;
          echo json_encode($response);
          $alertString = '<div class="alert alert-danger">
          <strong>Error: </strong> Incorrect Email
          </div>';
        }
      }
      // incorrect password
      else{
        //android incorrect password
        if(boolval($android)){
          $response = array();
          $response["message"] = "ERROR: incorrect password";
          $response["success"] = 0;
          echo json_encode($response);
          exit(0);
        }
        //web incorrect password
        else{
          $response = array();
          $response["message"] = "ERROR: incorrect password";
          $response["success"] = 0;
          echo json_encode($response);
          $alertString = '<div class="alert alert-danger">
          <strong>Error: </strong> Incorrect Password
          </div>';
        }
      }
    }
    // email not found
    else{
      //login failure
      //android login failure
      if(boolval($android)){
        $response = array();
        $response["message"] = "ERROR: no email + password found";
        $response["success"] = 0;
        echo json_encode($response);
        exit(0);
      }
      //web login failure
      else{
        $response = array();
        $response["message"] = "ERROR: no email + password found";
        $response["success"] = 0;
        echo json_encode($response);
        $alertString = '<div class="alert alert-danger">
          <strong>Error: </strong> Username does not exist
          </div>';
      }
    }
  }
?>
<?php include("../lib/php/header.php"); ?>
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


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

<body class="text-center">

<?php /*include("../lib/php/header.php"); */ ?>

<form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
  <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
  <label for="inputEmail" class="sr-only">Email address</label>
  <input type="email" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" required
         autofocus>
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
