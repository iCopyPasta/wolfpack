 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

 <?php if ($_SESSION['accountType'] == "teacher") { //redirects if not logged in as student
        // logged in
        
        } else {
        // not logged in as student
        header("Location: ../index.php");
        }

    if (! isset($_GET["class_id"])) {
        header("Location: ..\index.php");
    }
    $class_id = $_GET["class_id"];    
    include("../lib/php/confirmClassOwnership.php");
        
        if (! confirmClassOwnership($class_id,$_SESSION['id'])) {
            header("Location: ../index.php");
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

    <title>Polling</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="..\css\special\modalcss.css" media="screen" />  

    <!-- Custom styles for this template -->
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

.card-hover {
 cursor: pointer; 
     color:black;
    margin: 0 auto;
}
        
.card-hover:hover{
  background:teal;
    
 
}

  

.border-top { border-top: 1px solid #e5e5e5; }
.border-bottom { border-bottom: 1px solid #e5e5e5; }

.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    </style>
  </head>

  <body>

    <?php include("../lib/php/header.php"); ?>


    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-3 mx-auto text-center">
      <h1 class="display-4">Select Poll Set</h1>
        
    </div>
     

            
            
    <?php 
      include_once('../lib/php/Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT * FROM question_set WHERE teacher_id = ".$_SESSION['id'];
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        echo $e->getMessage();
      }
      
      $question_sets = $stmt->fetchAll(); //loads in result
      
      
      ?>
      
      
      <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px"><h5>Choose the question set to poll this class with.</h5></div>
      

    <div class="container">
    
    <h1 class="display-5 text-center"><a href="..\index.php"> <button type="button" class="btn btn-primary" >Return back to Dashboard</button></a></h1>
        <div class="card-deck mb-3 text-center">
        <?php
    
        
        
        foreach($question_sets as $value){
    
          echo "<a style= \"text-decoration: none;\" href=\"poll.php?class_id=".$class_id."&question_set_id=".$value['question_set_id']."\"><div class=\"card card-hover mb-4\" > 
         
          <div class=\"card-body\">
            <h3 class=\"card-title pricing-card-title\">".$value['question_set_name']."</h5>
          </div>
        </div></a>";
        }                               
            
        if (empty($question_sets)) {
            echo "<br><H3 class=\"display-5 text-center\">You have no question sets! Create one!</H3>";
        }
        
        ?>

      </div>
      


      <?php

        include("../lib/php/footer.php");
    ?>
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
