<?php

  /*
  This script is to be run from the page the teacher uses to create a section.

  It will insert a row into the class_section and has tables.

  Expectations from POST:
    class_section_number  -> a string
    offering              -> a string
    location              -> a string
    classId               -> a number; the course_id for which the teacher wants the new section to belong

  */

  $alertString="";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('/pages/Connection.php');
    include('/pages/C_ClassCourse.php');

    $class_section_number = isset($_POST['class_section_number']) ? $_POST['class_section_number'] : null;
    $offering = isset($_POST['offering']) ? $_POST['offering'] : null;
    $location = isset($_POST['location']) ? $_POST['location'] : null;
    $classId = isset($_POST['classId']) ? $_POST['classId'] : null;

    //TODO:remove the following test code
    $class_section_number = 1;
    $offering = 10;
    $location = "Olmstead";
    $classId = 30;
    //***********************************

    // ensure $class_section_number, $offering, $location, and $classId are not empty
    if(!empty($class_section_number)){
      if (!empty($offering)){
        if (!empty($location)){
          if(!empty($classId)) {
            // if classId exists then insert into section and has tables
            if (isClassIdExist($classId)) {
              // good things happen here!

              // insert new section into class_section table
              include_once('C_ClassSection.php');
              $section = new ClassSection('%', $class_section_number, $location, $offering);
              $section->insert();

              // insert new row into "has" table
              include_once('C_Has.php');
              $sectionRetValJSON = $section->select();
              $sectionRetVal = json_decode($sectionRetValJSON);

              // Does this "has" row already exist?  It shouldn't since we just created the section_id but we'll check anyway.
              // The problem is that the select from class_section may have returned an id that is not the one we just created.
              // This may reveal a problem with the schema.
              if(!isHasIdExist($classId, $sectionRetVal[1]->section_id)){
                $has = new Has($classId, $sectionRetVal[1]->section_id);
                $has->insert();
              }else{
                // row already exists in has table
                $response = array();
                $response["message"] = "ERROR: row ".$classId." ".$sectionRetVal[1]->section_id." already exists in table";
                $response["success"] = 0;
                echo json_encode($response);
              }
            }else {
              // class id does not exist
              $response = array();
              $response["message"] = "ERROR: class id " . $classId . " does not exist";
              $response["success"] = 0;
              echo json_encode($response);
            }
          }else{
            // $classId is empty
            $response = array();
            $response["message"] = "ERROR: classId is empty";
            $response["success"] = 0;
            echo json_encode($response);
          }
        }else {
          // $location is empty
          $response = array();
          $response["message"] = "ERROR: location is empty";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }else {
        // $offering is empty
        $response = array();
        $response["message"] = "ERROR: offering is empty";
        $response["success"] = 0;
        echo json_encode($response);
      }
    }
    else{
      // $class_section_number is empty
      $response = array();
      $response["message"] = "ERROR: class_section_number is empty";
      $response["success"] = 0;
      echo json_encode($response);
    }
  }

  function isClassIdExist($aClassId){
    include_once('/pages/C_ClassCourse.php');
    $course = new ClassCourse($aClassId, '%', '%', '%', '%');
    $qJSON = json_decode($course->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isSectionIdExist($aSectionId){
    include_once('/pages/C_ClassSection.php');
    $section = new ClassSection($aSectionId, '%', '%', '%');
    $qJSON = json_decode($section->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isHasIdExist($aClassId, $aSectionId){
    include_once('/pages/C_Has.php');
    $has = new Has($aClassId, $aSectionId);
    $qJSON = json_decode($has->select(), true);
    // if a row was returned then the hasId exists
    return array_key_exists(1, $qJSON);
  }
?>