<?php


  function addStudentIntoClass($student_id, $class_id){
    include('lib/php/isIdExistFunctions.php');
    //see if student is already in class (student_is_in table)
    $isStudentInClass = isStudentIsInExist($student_id, $class_id);

    if($isStudentInClass){
      // fail JSON response
      $response = array();
      $response["message"] = "ERROR: student:".$student_id." is already class:".$class_id;
      $response["success"] = 0;
      return json_encode($response);
    }

    $newStudentIsIn = new StudentIsIn($student_id, $class_id);
    return $newStudentIsIn->insert();
  }


?>
