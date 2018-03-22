<?php
  //TODO: possibly rename this isIdExistFunctions.php script to something more general like isFunctions.php; holding back for now so as not to create confusion

  function isStudentIdExist($aStudentId){
    include_once('C_StudentAccount.php');
    $student = new StudentAccount($aStudentId, '%', '%', '%', '%', '%', '%', '%');
    $qJSON = json_decode($student->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isClassIdExist($aClassId){
    include_once('C_ClassCourseSection.php');
    $class = new ClassCourseSection($aClassId, '%', '%', '%', '%');
    $qJSON = json_decode($class->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isQuestionSessionIdExist($aQSessionId){
    include_once('C_QuestionSession.php');
    $class = new QuestionSession($aQSessionId, '%', '%', '%', '%', '%');
    $qJSON = json_decode($class->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isQuestionHistoryIdExist($aQuestionHistoryId){
    include_once('C_QuestionHistory.php');
    $questionHist = new QuestionHistory($aQuestionHistoryId, '%', '%');
    $qJSON = json_decode($questionHist->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isQuestionSetIdExist($aQuestionSetId){
    include_once('C_QuestionSet.php');
    $questionSet = new QuestionSet($aQuestionSetId, '%', '%');
    $qJSON = json_decode($questionSet->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isQuestionIdExist($aQuestionId){
    include_once('C_Question.php');
    $question = new Question($aQuestionId, '%', '%', '%', '%', '%');
    $qJSON = json_decode($question->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isTeacherIdExist($aTeacherId){
  include_once('C_TeacherAccount.php');
  $teacher = new TeacherAccount($aTeacherId, '%', '%', '%', '%', '%', '%', '%');
  $qJSON = json_decode($teacher->select(), true);
  // if a row was returned then the class_id exists
  return array_key_exists(1, $qJSON);
  }

  function isStudentIsInExist($aStudentId, $aClassId){
    include_once('C_StudentIsIn.php');
    $teacher = new StudentIsIn($aStudentId, $aClassId);
    $qJSON = json_decode($teacher->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isStudentConfirmed($email){
    include_once('C_StudentAccount.php');
    $student = new StudentAccount('%', '%', '%', '%', $email, '%', '%', '%');
    $qJSON = json_decode($student->select(), true);
    // if a row was returned then the class_id exists
    if($qJSON[1]['is_confirmed'] == 1) return true;
    else return false;
  }
?>
