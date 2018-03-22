 <?php //include this code at the top of all pages with special functionality for a logged in user
 session_start(); ?>

 <?php if ($_SESSION['accountType'] == "teacher") { //redirects if not logged in as student
        // logged in
        
        } else {
        // not logged in as student
        header("Location: ..\index.php");
        }

        $class_id = $_GET["class_id"];    
        $question_set_id = $_GET["question_set_id"];
        include("..\lib\php\confirmClassOwnership.php");
        
        if (! confirmClassOwnership($class_id,$_SESSION['id'])) {
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

.border-top { border-top: 1px solid #e5e5e5; }
.border-bottom { border-bottom: 1px solid #e5e5e5; }

.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    </style>
  </head>

  <body>

    <?php include("../lib/php/header.php"); ?>
      <?php include("../lib/php/selectQuestionsByTeacherID.php"); ?>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-3 mx-auto text-center">
      <h1 class="display-4">Poll</h1>
    </div>
     
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px">
        <h5>Set the poll as active, allowing your students to join. Then, activate a question by clicking its button to broadcast that question.</h5>
        <a style="text-decoration: none"> <button onclick="setPollActive()" id="togglePollLive" class="btn btn-success btn-lg btn-block ">Set this Poll as Active</button></a>
    </div>
      
      

    <div class="container">
    <h1 class="display-5 text-center">Pollable Questions</h1>
        <div class="card-deck mb-3 text-center">
        <?php
        
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
    
        $sql = "SELECT * FROM question_is_in,question WHERE question_is_in.question_set_id = '$question_set_id' AND question.question_id = question_is_in.question_id";
        $stmt = $pdo->prepare($sql);
            
        try{
            $stmt->execute();
            }
            catch (Exception $e){
            // fail JSON response
            echo $e->getMessage();

            die();
        }
            
        $pdo = null;
        
        
        $result = $stmt->fetchAll(); //loads in result
        
        foreach($result as $value){
          $question_type = $value['question_type'];
          $description = $value['description'];
          $question_id= $value['question_id'];
          echo "<div class=\"card mb-4\">
          <div class=\"card-header\">
            <h4 class=\"my-0 font-weight-normal\">Question Type: $question_type</h4>
          </div>
          <div class=\"card-body\">
            <h5 class=\"card-title pricing-card-title\">$description</h5>
            <button type=\"button\" name=\"HELLO\" id=\"$question_id\" onclick=\"startPoll($question_id)\" class=\"btn btn-lg btn-block btn-inactive\" disabled>Set Question Active</button>
          </div>
        </div>";
        }                               
            
        if (empty($result)) {
            echo "<br><H3 class=\"display-5 text-center\">This Question Set has no Questions! Bummer!</H3>";
        }
        
        ?>

      </div>
      
      <script>
          
          var isPollActive = 0; //javascript sucks and cant do booleans right, so C-style bool
          var activeQuestionId = null;
          var questionSessionID;
          var currentQuestionHistoryID;
        
        function setPollActive() { //session
            var i = 0;
            
        
            
            
            var buttons = document.getElementsByName("HELLO");
            var mainButton = document.getElementById("togglePollLive");
            
            console.log("number of buttons: "+buttons.length);
            
            for (i = 0; i < buttons.length; ++i) {
                console.log("iteration");
                buttons[i].className = "btn btn-lg btn-block btn-success";
                buttons[i].disabled = false;
            }
            
            mainButton.className = "btn btn-danger btn-lg btn-block";
            mainButton.setAttribute('onclick','setPollInactive()');
            mainButton.innerHTML= "Set this Poll as Inactive";

            
            var class_id = <?php echo $class_id; ?>;
            var question_set_id = <?php echo $question_set_id; ?>;
            
            var params = "class_id="+class_id+"&question_set_id="+question_set_id;  //create the session object
            questionSessionID = postSync('../lib/php/generateQuestionSession.php',params);
            
            //console.log("response from server: "+questionSessionID);
            
            var activity = 1;
            params = "class_id="+class_id+"&question_set_id="+question_set_id+"&activity="+activity; //TODO: modify DB and add question session ID to this table to let student retrieve it
           
            post('../lib/php/toggleActiveSessionWeb.php',params);
            
            isPollActive = 1;
        }
          
          
        function setPollInactive() { //session
            
            if (activeQuestionId != null) {
                stopPoll(activeQuestionId);
            }
            
            if (isPollActive == 1) {
                var i = 0;
                var buttons = document.getElementsByName("HELLO");
                var mainButton = document.getElementById("togglePollLive");

                console.log("number of buttons: "+buttons.length);

                for (i = 0; i < buttons.length; ++i) {
                    console.log("iteration");
                    buttons[i].className = "btn btn-lg btn-block btn-inactive";
                    buttons[i].disabled = true;
                }

                mainButton.className = "btn btn-success btn-lg btn-block";
                mainButton.setAttribute('onclick','setPollActive()');

                mainButton.innerHTML = "Set this Poll as Active";


                var class_id = <?php echo $class_id; ?>;
                var question_set_id = <?php echo $question_set_id; ?>;
                var activity = 0;
                var params = "class_id="+class_id+"&question_set_id="+question_set_id+"&activity="+activity;
                console.log(params);
                post('../lib/php/toggleActiveSessionWeb.php',params);

                isPollActive = 0;
            }
            
            
        }  
          
          function startPoll(buttonId) { //question
            var i = 0;
            var buttons = document.getElementsByName("HELLO");
            var thisButton = document.getElementById(buttonId);
            
            console.log("number of buttons: "+buttons.length);
            
            for (i = 0; i < buttons.length; ++i) {
                console.log("iteration");
                if (buttons[i].id != thisButton.id) {
                    buttons[i].className = "btn btn-lg btn-block btn-inactive";
                    buttons[i].disabled = true;
                }
            }
              
              
            
            thisButton.className = "btn btn-danger btn-lg btn-block";
             thisButton.setAttribute('onclick',"stopPoll("+buttonId+")");

             thisButton.innerHTML = "Set Question Inactive";
            
             var question_id = buttonId;
             var question_set_id = <?php echo $question_set_id; ?>;
             var activity = 1;
              
              
              
             var params = "session_id="+questionSessionID+"&question_id="+question_id;  //create the session object
             currentQuestionHistoryID = postSync('../lib/php/generateQuestionHistoryEntry.php',params);  
              
              
              
             params = "question_id="+question_id+"&question_set_id="+question_set_id+"&activity="+activity; //TODO: add currentQuestionHistoryID into this table by modifying database so that the student can retrieve it
             console.log(params);
             post('../lib/php/toggleActiveQuestionWeb.php',params); 
              
              
              activeQuestionId = buttonId;
        }  
          
          function stopPoll(buttonId) { //question
              
            if (activeQuestionId != null) {  
                var i = 0;
                var buttons = document.getElementsByName("HELLO");
                var thisButton = document.getElementById(buttonId);

                console.log("number of buttons: "+buttons.length);

                for (i = 0; i < buttons.length; ++i) {
                    console.log("iteration");
                    if (buttons[i].id != thisButton.id) {
                        buttons[i].className = "btn btn-lg btn-block btn-success";
                        buttons[i].disabled = false;
                    }
                }



                thisButton.className = "btn btn-success btn-lg btn-block";
                thisButton.setAttribute('onclick',"startPoll("+buttonId+")");

                thisButton.innerHTML = "Set Question Active";

                var question_id = buttonId;
                var question_set_id = <?php echo $question_set_id; ?>;
                var activity = 0;
                var params = "question_id="+question_id+"&question_set_id="+question_set_id+"&activity="+activity;
                console.log(params);
                post('../lib/php/toggleActiveQuestionWeb.php',params); 

                activeQuestionId = null;
                }  
          }
        
        
        
        function postSync(url,params) {
               var xhttp = new XMLHttpRequest();
               xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    return this.responseText;
                    }
                }
               
                xhttp.open("POST", url, false);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(params);
                return xhttp.onreadystatechange();
            }
          
          
          function post(url,params) {
               var xhttp = new XMLHttpRequest();
               
                xhttp.open("POST", url, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(params);
            }
          
          
          
          
        
        window.onbeforeunload = function() { //clear the database if the user closes the page without closing session first
            if (isPollActive == 1) {
                if (!confirm("Are you sure you wish to leave the page? You have an active poll running.")) {
                    return false;
                }
                else {
                    setPollInactive();
                }
            }
            
                
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
