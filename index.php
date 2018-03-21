 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

 <?php if (isset($_SESSION['accountType']) && $_SESSION['accountType'] == "student") { //logged in as student
        header("Location: pages/logged_in_student.php");
        
        }
        if  (isset($_SESSION['accountType']) && $_SESSION['accountType'] == "teacher") { //logged in as teacher
        header("Location: pages/logged_in_teacher.php");
            
        }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" >
    <meta name="author" content="">
  

    <title>Pollato</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <style>
    
        body {
        padding-top: 3.5rem;
        }
    </style>
  </head>

  <body>   
    <?php
        include("lib/php/header_index.php");
    ?> 

    <main role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <div class="container">
          <h1 class="display-3">Pollato</h1>
          <h5 style="padding-left:2rem">A new way to teach.</h5>
          <p><a class="btn btn-primary btn-lg" href="pages/Sign_up.php" role="button">Student Sign up &raquo;</a></p>
          <p><a class="btn btn-primary btn-lg" href="pages/Sign_up_teacher.php" role="button">Teacher Sign up &raquo;</a></p>
        </div>
      </div>

      <div class="container">
        <!-- Example row of columns -->
        <div class="row">
          <div class="col-md-6">
            <h2>Instructors</h2>
            <p>Explore the classroom of the future.</p>
            <p><a class="btn btn-secondary" href="pages/Sign_in_teacher.php" role="button">Sign in as Teacher &raquo;</a></p>
          </div>
          <div class="col-md-6">
            <h2>Students</h2>
            <p>Learn like never before.</p>
            <p><a class="btn btn-secondary" href="pages/Sign_in_student.php" role="button">Sign in as Student &raquo;</a></p>
          </div>

        </div>

        <hr>

      </div> <!-- /container -->

    </main>

    <?php
        $page_footer_text = "Credit: Twitter bootstrap 4.0 documentation. Working Demo.";

        include("lib/php/footer_index.php");
    ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../../../assets/js/vendor/popper.min.js"></script>
    <script src="../../../../dist/js/bootstrap.min.js"></script>
  </body>
</html>

