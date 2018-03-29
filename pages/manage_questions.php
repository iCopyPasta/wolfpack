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
    

    <title>Question Manager</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="..\css\special\modalcss.css" media="screen" />  
    
    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>

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

.flex-box {

}

.pricing-header {
  max-width: 700px;
}

.card-deck .card {
  min-width: 500px;
}

.border-top { border-top: 1px solid #e5e5e5; }
.border-bottom { border-bottom: 1px solid #e5e5e5; }

.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    </style>
  </head>

  <body>

    <?php include("../lib/php/header.php"); ?>
      <?php include("../lib/php/selectQuestionsByTeacherID.php"); ?>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-3 mx-auto text-center">
      <h1 class="display-4">Question Manager</h1>
    </div>
     
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px">
        <a style="text-decoration: none" href ="create_question_set.php"> <button class="btn btn-info btn-lg btn-block" >Create New Question Set</button></a><br>
        <a style="text-decoration: none"> <button id="createQuestionButton" class="btn btn-info btn-lg btn-block">Create New Question</button></a>
    </div>
      
      <div id="myModal" class="modal">

          <!-- Modal content -->
          <div class="modal-content">
            <div class="modal-header">
                <h2>Create a Question</h2>
              <span class="close">&times;</span>
              
            </div>
            <div class="modal-body">
              <?php include("createQuestionFragment.php"); ?>
            </div>

          </div>

    </div>
      
      <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the button that opens the modal
        var btn = document.getElementById("createQuestionButton");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
      </script>
      
      
      

    <div class="flex-box">
        <h1 class="display-5 text-center">Current Questions</h1>
        <div class="card-deck" id="questionDeck">
          <?php

           $retVal = searchQuestionsByTeacherID($_SESSION['id']);
           $retVal = json_decode($retVal,true);
           $removeZerothIndex = $retVal;
           unset($removeZerothIndex[0]);

          foreach($removeZerothIndex as $value){
            $question_type = $value['question_type'];
            $description = $value['description'];

            $answers = json_decode($value['potential_answers'], TRUE);
            if(is_null($answers) || empty($answers))
              $answers = "There are no answers!";
            else
            {
              $answers = array_slice($answers, 0, 2);
              $answers = implode("<br>", $answers);
            }
              

            $correct_answers = json_decode($value['correct_answers'], TRUE);
            if(is_null($correct_answers) || empty($correct_answers))
              $correct_answers = "There are no correct answers!";
            else
              $correct_answers = implode(" ", $correct_answers);


            echo "<div class=\"card bg-secondary text-white mb-3\">
            <div class=\"card-header\">
              <button type=\"button\" class=\"btn btn-danger btn-sm float-right\">
                <span class=\"fas fa-trash fa-sm\"></span>
              </button>
              <h4 class=\"my-0 font-weight-normal text-truncate\">$description</h4>
            </div>
            <div class=\"card-body\">
              <h5 class=\"card-title pricing-card-title text-truncate\">$answers</h5>
            </div>
            </div>";        
          }                               

          if (empty($removeZerothIndex)) {
              echo "<h3 class=\"display-5 text-center\">Get started by creating some questions!</h3>";
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
