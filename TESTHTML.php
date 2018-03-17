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
        include('/pages/Connection.php');
        include('/pages/C_ClassCourse.php');
        include('/pages/C_ClassSection.php');
        include('/pages/C_Has.php');
        include('/pages/C_IsIn.php');
        include('/pages/C_ProfessorAccount.php');
        include('/pages/C_Teaches.php');
        include('/pages/searchClassTitleSectionByTeacher.php');
        include('/pages/searchClassesByStudent.php');

        // select a course
        $newCourse = new ClassCourse('%', '122', 'Olmstead 100', 'whatIsOffering?', 'Object Oriented Programming');
        echo indent($newCourse->select());


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

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    function indent($json) {

      $result      = '';
      $pos         = 0;
      $strLen      = strlen($json);
      $indentStr   = '  ';
      $newLine     = "\n";
      $prevChar    = '';
      $outOfQuotes = true;

      for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
          $outOfQuotes = !$outOfQuotes;

          // If this character is the end of an element,
          // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
          $result .= $newLine;
          $pos --;
          for ($j=0; $j<$pos; $j++) {
            $result .= $indentStr;
          }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
          $result .= $newLine;
          if ($char == '{' || $char == '[') {
            $pos ++;
          }

          for ($j = 0; $j < $pos; $j++) {
            $result .= $indentStr;
          }
        }

        $prevChar = $char;
      }

      return $result;
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
