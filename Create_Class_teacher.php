<?php
  $host = 'localhost';
  $user = 'root';
  $password = '';
  $dbname = 'iClicker';

  // Set DSN
  $dsn = 'mysql:host='.$host.';dbname='.$dbname;

  // Create a PDO instance
  $pdo = new PDO($dsn, $user, $password);

  // PDO INSERT Statement
  $class_course_number = $_POST['class_course_number'];
  $location = $_POST['location'];
  $offering = $_POST['offering'];

  $sql = "INSERT INTO class_course
                          (class_course_number, location, offering)
                          VALUES (:class_course_number, :location, :offering)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['class_course_number' => $class_course_number, 'location' => $location, 'offering' => $offering]);
  echo 'Post Added <br>'.$class_course_number.' '.$location.' '.$offering;

?>
