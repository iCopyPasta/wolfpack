<?php
  /*
  This class is used to insert and select from table "is_in".
  _______________
  |is_in         |
  |______________|
  |student_id    |
  |class_id      |
  |--------------|
  |______________|

  This table has a composite primary key built from the student_account and class_course table.
  For this reason, checks are made against those tables to ensure the student_id and class_id given exist in their respective tables.

  Example Usage:

  // insert a new "is_in"
    $aIsIn = new IsIn(1, 15);
    $response = $aIsIn->insert();

  // selecting a "is_in"; 0th element is success message; 1th to end are rows from table
    $aIsIn = new IsIn(1, 15);
    $response = $aIsIn->select();

  // to test for no rows existing
    $aIsIn = new IsIn(1, 15);
    $qJSON = json_decode($aIsIn->select(), true);
    if(array_key_exists(1, $qJSON)){
      echo 'exists<br>';
    }
    else{
      echo 'no exists<br>';
    }

*/

  class OwnsQuestion{
    private $question_id;
    private $teacher_id;

    function __construct($question_id, $teacher_id) {
      $this->__set('question_id',$question_id);
      $this->__set('teacher_id',$teacher_id);
    }

    // magical get
    // reference: http://php.net/manual/en/language.oop5.magic.php
    public function __get($property) {
      if (property_exists($this, $property)) {
        return $this->$property;
      }
    }

    // magical set
    // reference: http://php.net/manual/en/language.oop5.magic.php
    public function __set($property, $value) {
      if (property_exists($this, $property)) {
        $this->$property = $value;
      }
      return $this;
    }

    public function insert(){
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "INSERT INTO owns_question
                              (question_id, teacher_id)
                              VALUES (:question_id, :teacher_id)";
      $stmt = $pdo->prepare($sql);
      include_once('isIdExistFunctions.php');
      $isQuestionIdExist = isQuestionIdExist($this->__get('question_id'));
      $isTeacherIdExist = isTeacherIdExist($this->__get('teacher_id'));

      if($isQuestionIdExist){
        if($isTeacherIdExist){
          // question_id and teacher_id exist; attempt to insert
          try{
            $stmt->execute(['question_id' => $this->question_id, 'teacher_id' => $this->teacher_id]);
          }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->question_id." ".$this->teacher_id." ".$e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
          }

          // success JSON response
          $response = array();
          $response["message"] = "Inserted: ".$this->question_id." ".$this->teacher_id;
          $response["success"] = 1;
          echo json_encode($response);

          $pdo = null;
        }else{
          //build response for no teacher id
          $response = array();
          $response["message"] = "ERROR INSERTING into owns_question table: teacher_id ".$this->teacher_id." does not exist in student_account table";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }
      else{
        // build response for no question id
        $response = array();
        $response["message"] = "ERROR INSERTING into owns_question table: question_id ".$this->question_id." does not exist in student_account table";
        $response["success"] = 0;
        echo json_encode($response);
      }
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_id, teacher_id
              FROM owns_question
              WHERE question_id = :question_id
                AND teacher_id = :teacher_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_id', $this->question_id);
      $stmt->bindValue(':teacher_id', $this->teacher_id);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR SELECTING: ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      $pdo = null;
      $response = array();
      $response["message"] = "Success SELECTING from owns_question";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }
  }
?>
