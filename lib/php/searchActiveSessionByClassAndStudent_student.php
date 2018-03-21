<?php
  /*
    function searchActiveSessionByClassAndStudent
    arguments:  page - the current page
                rowsPerPage - rows per page
                class_id
                student_id
    return: JSON obj containing question_session.id

    Example Usage:
    $retVal = searchActiveSessionByClassAndStudent('1', '1', '13', '1');

    Example Return Value:
    [{"message":"Success SELECTING active question session","success":1},{"id":"1"}]

  */


  function searchActiveSessionByClassAndStudent($page, $rowsPerPage, $class_id, $student_id){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    //get current date and time
    // reference: http://php.net/manual/en/function.date.php
    date_default_timezone_set('America/New_York'); // CDT
    $currDate = date("Y-m-d H:i:s"); // "Y-m-d H:i:s" PHP format is the same as the MySQL timestamp format'YYYY-MM-DD HH:MM:SS'

//    //poll has started: current time is after start time
//    if($currDate > $start){
//      //poll hasn't ended yet: current time is before end time
//      if($currDate < $end){
//        return true;
//      }
//      //poll has ended: current time is after end time
//      else{
//        return false;
//      }
//    }
//    //poll hasn't started yet: current time is before start time
//    else{
//      return false;
//    }


    $sql = "SELECT question_session.id
            FROM student_account, student_is_in, class_course_section, question_session
            WHERE student_account.student_id = student_is_in.student_id
              AND student_is_in.class_id = class_course_section.class_id
              AND class_course_section.class_id = question_session.class_id
              AND question_session.start_time < :currDate
              AND question_session.end_time > :currDate
              AND student_account.student_id = :student_id
              AND class_course_section.class_id = :class_id 
            ";



    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':class_id', $class_id);
    $stmt->bindValue(':student_id', $student_id);
    $stmt->bindValue(':currDate', $currDate);


    try{
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
    $response["message"] = "Success SELECTING active question session";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>