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

    <title>Question Set</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="..\css\special\modalcss.css" media="screen" />  
    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="..\css\common\custom.css">
    <!-- Custom styles for this template -->
    <style>
      html
      {
        font-size: 14px;
      }
      @media (min-width: 768px)
      {
        html
        {
          font-size: 16px;
        }
      }
      .flex-box
      {
        min-width: 500px;
        padding-left: 20px;
        padding-right: 20px;
      }
      .pricing-header
      {
        max-width: 700px;
      }
      .card-deck .card
      {
        min-width: 500px;
      }
      .clickBox
      {
        cursor: pointer;
      }
      .selected-box
      {
        background: #138496;
        border-width: thick;
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
      <h1 class="display-4">Create Question Set</h1>
    </div>
     
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px">
        <h5>Select the questions to add to this question set below by clicking them and submit when finished.</h5>
      
        <div class="form-group">
        <label for="question_set_name">
          Question Set Name:</label>
        <input type="text" class="form-control" id="description" name="description" required>  <div id="error"></div>
      </div>
            <button type="button" class="btn btn-primary" onclick="sendQuestionSet()">Submit Question Set</button>
            
    </div>

    <div class="flex-box">
    <h1 class="display-5 text-center">Current Questions</h1>
        <div class="card-deck mb-3 text-center">
        <?php
        
           $retVal = searchQuestionsByTeacherID($_SESSION['id']);
           $retVal = json_decode($retVal,true);
           $removeZerothIndex = $retVal;
           unset($removeZerothIndex[0]);

          foreach($removeZerothIndex as $value){
            $question_type = $value['question_type'];
            $description = $value['description'];
            $question_id = $value['question_id'];

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
             
            
          echo "<div class=\" clickBox card bg-secondary text-white mb-3\" id=\"$question_id\" onclick=\"toggleActive($question_id)\"> 
                  <div class=\"card-header\">
                    <h4 class=\"my-0 font-weight-normal text-truncate\">$description</h4>
                  </div>
                  <div class=\"card-body\">
                    <h5 class=\"card-title pricing-card-title text-truncate\">$answers</h5>
                  </div>
                </div>";
        }                               
            
        if (empty($removeZerothIndex)) {
            echo "<br><H3 class=\"display-5 text-center\">You have no questions! Create one!</H3>";
        }
        
        ?>

      </div>
      
    <script>
        var activeQuestions = new Set();
        
        function toggleActive(question_id) {
            
            if (activeQuestions.has(question_id)) {
                document.getElementById(question_id).className = "clickBox card bg-secondary text-white mb-3";
                activeQuestions.delete(question_id);
            }
            else {
                document.getElementById(question_id).className = "clickBox card mb-3 text-white selected-box border-dark";
                activeQuestions.add(question_id);
            }
                
                
        }
        
        function sendQuestionSet() {
            var name = document.getElementById("description");
            var error = document.getElementById("error");
            //var questions = JSON.stringify(Array.from(activeQuestions));
            if (name.value === "") {
                error.innerHTML = "<p style=\"color:red\">Please provide a name for your question set.</p>";
            }
            else { //send the request to the server
                console.log("name: " + name.value);
                console.log(activeQuestions);
                
                var send = new Object();
                send.question_set_name = name.value;
                send.questions = JSON.stringify(Array.from(activeQuestions));
                post('../lib/php/createQuestionSetWeb.php',send);
            }
        }
        
        
        
        function post(path, params, method) { // method: https://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
            method = method || "post"; // Set method to post by default if not specified.

            // The rest of this code assumes you are not using a library.
            // It can be made less wordy if you use one.
            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", path);

            for(var key in params) {
                if(params.hasOwnProperty(key)) {
                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", key);
                    hiddenField.setAttribute("value", params[key]);

                    form.appendChild(hiddenField);
                }
            }

            document.body.appendChild(form);
            form.submit();
        }
        
        
        </script>    
        
      

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
