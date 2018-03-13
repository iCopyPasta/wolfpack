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

  <?php
      $alertString="";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include('/pages/C_View_Question.php');
        $viewQuestion = new ViewQuestion('%','%','%','%');
        $qJSON = json_decode($viewQuestion->select());
        // var_dump($qJSON, true);
        // foreach($qJSON as $key => $value){
        //   echo "$key => $value"."<br>";
        // }
        echo json_encode($qJSON);

      }
  ?>

  <body>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <div class="form-group">
        <label for="class_course_number">Class Course Number:</label>
        <input type="text" class="form-control" id="class_course_number" name="class_course_number">
      </div>
      <div class="form-group">
        <label for="location">Location:</label>
        <input type="text" class="form-control" id="location" name="location">
      </div>
      <div class="form-group">
        <label for="offering">Offering:</label>
        <input type="text" class="form-control" id="offering" name="offering">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>

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