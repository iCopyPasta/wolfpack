<?php
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

  //TODO: create C_QuestionSession.php
  function isQuestionSessionIdExist($aQSessionId){
    include_once('C_QuestionSession.php');
    $class = new QuestionSession($aQSessionId, '%', '%', '%', '%', '%');
    $qJSON = json_decode($class->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  //TODO: create C_QuestionHistory.php
  function isQuestionHistoryIdExist($aQuestionHistoryId){
    include_once('C_QuestionHistory.php');
    $questionHist = new QuestionHistory($aQuestionHistoryId, '%', '%');
    $qJSON = json_decode($questionHist->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  //TODO: create C_QuestionSet.php
  function isQuestionSetIdExist($aQuestionSetId){
    include_once('C_QuestionSet.php');
    $questionSet = new QuestionSet($aQuestionSetId, '%');
    $qJSON = json_decode($questionSet->select(), true);
    // if a row was returned then the class_id exists
    return array_key_exists(1, $qJSON);
  }

  function isQuestionIdExist($aQuestionId){
    include_once('C_Question.php');
    $question = new Question($aQuestionId, '%', '%', '%', '%');
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




?>