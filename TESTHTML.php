<?php

$alertString="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  include('lib/php/Connection.php');
  include('lib/php/C_Answers.php');
  include('lib/php/C_ClassCourseSection.php');

  //ensure $session_id is populated
  $class_id = isset($_POST['class_id']) ? $_POST['class_id'] : null;
  if (is_null($class_id) || empty($class_id)) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR, $class_id cannot be null or empty";
    $response["success"] = 0;
//          return json_encode($response);
//          echo json_encode($response);
    exit();
  }

  $date = date('YMdHis');
  #$targetDirectory = "\"C:/wamp64/tmp/ClassReport".$date.".csv\"";
  $targetDirectory = "/var/lib/mysql-files/ClassReport".$date.".csv";
  $sql = "SELECT `Date`, `Session_Id`, `Student_Id`, `First_Name`, `Last_Name`, `Score`
                FROM(
                  (SELECT 1 as Sort_Value, 'Date', 'Session_Id', 'Student_Id', 'First_Name', 'Last_Name', 'Score')
                  UNION ALL
                  (SELECT 2 as Sort_Value, totalQuestionsPerSession.start_time as 'Date', num_correct.session_id as Session_Id, num_correct.student_id as Student_Id, student_account.first_name as First_Name, student_account.last_name as Last_Name, TRUNCATE(num_correct.numberCorrect / totalQuestionsPerSession.total,3) as Score
                  FROM student_account,
                    ( SELECT answers.student_id, question_history.session_id, count(*) AS numberCorrect
                      FROM answers, question_history, question, student_account, student_is_in, class_course_section
                      WHERE answers.question_history_id = question_history.id
                      AND question_history.question_id = question.question_id
                      AND answers.answer = question.correct_answers
                      AND answers.student_id = student_account.student_id
                      AND student_account.student_id = student_is_in.student_id
                      AND student_is_in.class_id = class_course_section.class_id
                      AND class_course_section.class_id = :class_id
                      GROUP BY question_history.session_id, answers.student_id
                      ORDER BY answers.student_id
                    ) AS num_correct,
                    ( SELECT question_session.start_time, question_history.session_id, count(*) AS total
                      FROM question_history, question_session, class_course_section
                      WHERE question_history.session_id = question_session.id
                      AND question_session.class_id = class_course_section.class_id
                      AND class_course_section.class_id = :class_id
                      GROUP BY question_history.session_id
                    ) AS totalQuestionsPerSession
                  WHERE num_correct.session_id = totalQuestionsPerSession.session_id
                  AND student_account.student_id = num_correct.student_id
                  ORDER BY num_correct.session_id, num_correct.student_id)) as tbl
                ORDER BY Sort_Value, `Date`, `Last_Name`, `First_Name`
                INTO  OUTFILE :location
                      FIELDS TERMINATED BY ',' 
                      LINES TERMINATED BY '\n'
                ";

  $connection = new Connection;
  $pdo = $connection->getConnection();
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':class_id', $class_id);
  $stmt->bindValue(':location', $targetDirectory, PDO::PARAM_STR);

  try {
    $stmt->execute();
  } catch (Exception $e) {
    // fail JSON response
    $response = array();
    $response["message"] = "ERROR SELECTING: " . $e->getMessage();
    $response["success"] = 0;
//          return json_encode($response);
//          echo json_encode($response);
    exit();
  }

  // success JSON response
  $response = array();
  $response["message"] = "Success selecting";
  $response["success"] = 1;


  #echo "MyUID-IS: ".posix_getpwuid(posix_geteuid())['name'];
  
  #echo "<br><br>";
  #echo exec('whoami'); 

  $file = $targetDirectory;
  #www-data --> mysql?
  #)
  if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    flush();
    readfile($file);
    exit();
  } else{
     echo "rest in pieces<br>";
  }


//        return json_encode($response);
  echo json_encode($response);
  exit();
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

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <div class="form-group">
        <label for="class_id">class_id:</label>
        <input type="text" class="form-control" id="class_id" name="class_id">
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
