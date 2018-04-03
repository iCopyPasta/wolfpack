<?php
  /* ALL OF THIS EXCEPT STUDENT INSTEAD:
    //TODO: documentation and usage
    function searchClassTitleSectionByTeacher
    arguments:  teacherID - the teacher_id of the teacher to get all classes for
    return: JSON obj containing course title and course section number

    Example Usage:
    $retVal = searchClassesTaught('1');
    $retVal = json_decode($retVal);
    $removeZerothIndex = $retVal;
    unset($removeZerothIndex[0]);

    foreach($removeZerothIndex as $value){
      $classId = $retVal[1]->class_id;
      $title = $retVal[1]->title;
      $description = $retVal[1]->description;
      $offering = $retVal[1]->offering;
      $location = $retVal[1]->location;
      echo $classId.'<br>'.$title.'<br>'.$description.'<br>'.$offering.'<br>'.$location.'<br>';
    }

    if(array_key_exists(1, $retVal)) {
      $classId = $retVal[1]->class_id;
      $title = $retVal[1]->title;
      $description = $retVal[1]->description;
      $offering = $retVal[1]->offering;
      $location = $retVal[1]->location;
      echo $classId.'<br>'.$title.'<br>'.$description.'<br>'.$offering.'<br>'.$location.'<br>';
    }
    var_dump($retVal);

    Example Return Value:
    [{"message":"Success SELECTING from teacher_account, teaches, class_section, has, class_course","success":1},{"title":"Principles of Programming","class_section_number":"1"}]

  */
  function searchClassesTaken($studentID){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT class_course_section.class_id, class_course_section.title, class_course_section.description, class_course_section.offering, class_course_section.location
                FROM  student_is_in, class_course_section
                WHERE student_is_in.student_id = :studentID AND class_course_section.class_id = student_is_in.class_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':studentID', $studentID);


    try{
      // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
      $stmt->execute();
    }catch (Exception $e){
      // fail JSON response
      $response = array();
      $response["message"] = "ERROR SELECTING: ".$e->getMessage();
      $response["success"] = 0;
      // echo json_encode($response);
      // die();
      return json_encode($response);
    }

    $pdo = null;
    $response = array();
    $response["message"] = "Success SELECTING from student_is_in, class_course_section";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>