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

    <title>Class Manager</title>

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
  min-width: 200px;
}

.clickBox
{
  cursor: pointer;
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
      <h1 class="display-4">Class Manager</h1>
    </div>
     
    <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width:960px">
        <a style="text-decoration: none"> <button id="createClassButton" class="btn btn-info btn-lg btn-block" >Create New Class</button></a><br>
        <a style="text-decoration: none"> <button id="deleteQuestionButton" class="btn btn-danger btn-lg btn-block" onclick="deleteClassSet()">Delete Selected Classes</button></a>
    </div>
      
      <div id="myModal" class="modal">

          <!-- Modal content -->
          <div class="modal-content">
            <div class="modal-header">
                <h2>Create a Class</h2>
              <span class="close">&times;</span>
              
            </div>
            <div class="modal-body">
              <?php include("createClassFragment.php"); ?>
            </div>

          </div>

    </div>
      
      <script>
        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the button that opens the modal
        var btn = document.getElementById("createClassButton");

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
      
      
      

    <div class="container">
    <h1 class="display-5 text-center">Current Classes</h1>
        <div class="card-deck mb-3 text-center">
        <?php
        
         $retVal = searchClassesTaught($_SESSION['id']);
         $retVal = json_decode($retVal,true);
         $removeZerothIndex = $retVal;
         unset($removeZerothIndex[0]);
        
        
        foreach($removeZerothIndex as $value){
          $class_id = $value['class_id'];
          $title = $value['title'];
          $description = $value['description'];
          $offering = $value['offering'];
          $location = $value['location'];
          
          echo "<div class=\" clickBox card bg-secondary text-white mb-3\" id=\"$class_id\" onclick=\"toggleActive($class_id)\"> 
            <div class=\"card-header\">
              <button type=\"button\" class=\"btn btn-warning btn-sm float-right\" onclick=\"toggleActive($class_id);editClass($class_id)\">
                <span class=\"fas fa-pencil-alt\"></span>
              </button>
              <h4 class=\"my-0 font-weight-normal text-truncate\">$title</h4>
            </div>
            <div class=\"card-body\">
              <h5 class=\"card-title pricing-card-title text-truncate\">$location</h5>
            </div>
            <ul class=\"list-unstyled mt-3 mb-4\">
              <li>$offering</li>
            </ul>
            <div class=\"card-footer\">
              <a href=\"choosePolledQuestionSet.php?class_id=$class_id\"> <button type=\"button\" class=\"btn btn-lg btn-block btn-primary\">Poll Class</button></a>
            </div>
            </div>";     
        }                               //TODO: link poll class to the class poll using the proper parameter send structure
            
        if (empty($removeZerothIndex)) {
            echo "<br><h3 class=\"display-5 text-center\">You have no classes! Create one!</h3>";
        }
        
        ?>
      </div>     

      <?php include("../lib/php/footer.php"); ?>
        
        <script>
          var activeClasses = new Set();

          function toggleActive(class_id) {

              if (activeClasses.has(class_id)) {
                  document.getElementById(class_id).className = "clickBox card bg-secondary text-white mb-3";
                  activeClasses.delete(class_id);
              }
              else {
                  document.getElementById(class_id).className = "clickBox card mb-3 text-white selected-box";
                  activeClasses.add(class_id);
              }
          }
          
          function editClass(class_id)
          {
              
          }
          
          function deleteClassSet() {
              var name = document.getElementById("description");
              var error = document.getElementById("error");
              //var Classes = JSON.stringify(Array.from(activeClasses));
              if (name.value === "") {
                  error.innerHTML = "<p style=\"color:red\">Please provide a name for your question set.</p>";
              }
              else { //send the request to the server
                  console.log(activeClasses);

                  var send = new Object();
                  
                  //LOOP THROUGH ACTIVE CLASSES AND POST DELETE FOR EACH
                  
                  post('../lib/php/deleteClassCourseSection.php',send);
              }
          }



          function post(path, params, method) { // method: https://stackoverflow.com/querstions/133925/javascript-post-request-like-a-form-submit
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
