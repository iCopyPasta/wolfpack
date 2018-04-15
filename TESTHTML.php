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
        include('lib/php/updateQuestionSet.php');
        include('lib/php/deleteQuestionSet.php');
        include('lib/php/C_ClassCourseSection.php');

//        $question_set = new QuestionSet('%','1','testUpdateQuestionSet');
//        var_dump($question_set->insert());

        $class = new ClassCourseSection('276', '%', '%', '%', '%');
//        var_dump($class->insert());

        var_dump($class->update('testClass2', 'testClass2', 'testClass2', 'testClass2'));












//        $retVal = searchActiveSessionByClassAndStudent('1', '1', '13', '1');
//        var_dump($retVal);


//        // select a course
//        $newCourse = new ClassCourse('%', '122', 'Olmstead 100', 'whatIsOffering?', 'Object Oriented Programming');
//        echo indent($newCourse->select());


//
//        // select a section
//        $newSection = new ClassSection('thisWontMatterSinceItsThePK', '2', 'Olmstead 100', 'offering?');
//        $newSection->select();
//
//        // select a "has"
//        $aHas = new Has(1, 1);
//        $response = $aHas->select();
//
//        // select a professor
//        $newProfessor = new ProfessorAccount('%', 'Sukmoon', 'Chang', 'pw', 'schoolId', 'Chang1@psu.edu');
//        $newProfessor->select();
//        $newProfessor = new ProfessorAccount('%', 'Hyuntae', 'Na', 'pw', 'schoolId', 'Na1@psu.edu');
//        $newProfessor->select();
//        $newProfessor = new ProfessorAccount('%', 'Jeremy', 'Blum', 'pw', 'schoolId', 'Blum1@psu.edu');
//        $newProfessor->select();
//        $newProfessor = new ProfessorAccount('%', 'Linda', 'Kunkle', 'pw', 'schoolId', 'Kunkle1@psu.edu');
//        $newProfessor->select();
//
//        // select a teaches
//        $newTeaches = new Teaches(1, 1);
//        $newTeaches->select();
//
//        // select a is_in
//        $newIsIn = new IsIn(11, 1, 1);
//        $response = $newIsIn->select();
//
//        // search for all the classes a student is in
//        $search = searchClassesByStudent(1, 10, 11);
//
////        $search = searchClassesByStudent(1, 10, 11);
//        $qJSON = json_decode($search);
//        // var_dump($qJSON, true);
//        // foreach($qJSON as $key => $value){
//        //   echo "$key => $value"."<br>";
//        // }
//        echo json_encode($qJSON);
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
