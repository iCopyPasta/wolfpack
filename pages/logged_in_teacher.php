 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

 <?php if ($_SESSION['accountType'] == "teacher") { //redirects if not logged in as student
        // logged in
        
        } else {
        // not logged in as student
        header("Location: ..\index.php");
        }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Teacher Center</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="..\css\special\modalcss.css" media="screen" />  
    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="..\css\common\custom.css">
    
    <style>
      html {
        font-size: 14px;
      }
      @media (min-width: 768px) {
        html {
          font-size: 16px;
        }
      }

      .container {
        max-width: 960px;
      }

      .pricing-header {
        max-width: 700px;
      }

      .card-deck .card {
        min-width: 220px;
      }

      .border-top { border-top: 1px solid #e5e5e5; }
      .border-bottom { border-bottom: 1px solid #e5e5e5; }
      .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    </style>
  </head>

  <body>
    <?php include("../lib/php/header.php"); ?>
    <?php include("../lib/php/selectClassesTaught.php"); ?>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-3 mx-auto text-center">
      <h1 class="display-4">Teacher Center</h1>
      <?php echo "Welcome back, ".$_SESSION['user']."!"; ?>
    </div>
     
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px">
      <a style="text-decoration: none" href ="manage_questions.php"> <button class="btn btn-info btn-lg" >Manage My Questions</button></a>
      <a style="text-decoration: none" href ="manage_class.php"> <button id="manageClassButton" class="btn btn-info btn-lg" >Manage My Classes</button></a><br>
    </div>
    
    <div class="container">
        <h1 class="display-5 text-center">Current Classes</h1>
      
        <div class="card-deck mb-3 text-center">
        <?php
        
         $retVal = searchClassesTaught($_SESSION['id']);
         $retVal = json_decode($retVal,true);
         $removeZerothIndex = $retVal;
         unset($removeZerothIndex[0]);
        
        
        foreach($removeZerothIndex as $value){
          $classId = $value['class_id'];
          $title = $value['title'];
          $description = $value['description'];
          $offering = $value['offering'];
          $location = $value['location'];
          
          echo
          "<div class=\"card mb-3 text-white bg-secondary box-shadow\">
            <div class=\"card-header\">
              <h2 class=\"my-0 text-truncate font-weight-normal\">$title</h2>
            </div>
            <div class=\"card-body\">
              <h3 class=\"card-title text-truncate pricing-card-title\"><small>$location</small></h3>
            </div>
            <ul class=\"list-unstyled mt-3 mb-4\">
              <li>$offering</li>
            </ul>
            <div class=\"card-footer\">
              <a href=\"choosePolledQuestionSet.php?class_id=$classId\"> <button type=\"button\" class=\"btn btn-lg btn-block btn-primary\">Poll Class</button></a>
            </div>
          </div>";
        }                               
            
        if (empty($removeZerothIndex)) {
            echo "<br><h3 class=\"display-5 text-center\">You have no classes! Create one!</h3>";
        }
        
        ?>
      </div>
      
      <?php include("../lib/php/footer.php"); ?>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../../../../assets/js/vendor/popper.min.js"></script>
    <script src="../../../../dist/js/bootstrap.min.js"></script>
    <script src="../../../../assets/js/vendor/holder.min.js"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
    </script>
  </body>
</html>
