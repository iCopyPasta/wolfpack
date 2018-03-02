<?php
  // PDO INSERT Statement
  include('Connection.php');
  $pdo = new Connection;

  if (!isset($myObj)) $myObj = new stdClass();
  $myObj->success = false;
  $myObj->class_course_number = $_POST['class_course_number'];
  $myObj->location = $_POST['location'];
  $myObj->offering = $_POST['offering'];
  $myJSON = json_encode($myObj);

  echo $myJSON;

  // $sql = "INSERT INTO class_course
  //                         (class_course_number, location, offering)
  //                         VALUES (:class_course_number, :location, :offering)";
  //
  // $stmt = $pdo->prepare($sql);
  // $stmt->execute(['class_course_number' => $class_course_number, 'location' => $location, 'offering' => $offering]);
  // echo 'Post Added <br>'.$class_course_number.' '.$location.' '.$offering;

?>
