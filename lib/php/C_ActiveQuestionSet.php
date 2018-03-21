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

  class ActiveQuestionSet{
    private $class_id;
    private $question_set_Id;

    function __construct($class_id, $question_set_Id) {
      $this->__set('class_id',$class_id);
      $this->__set('question_set_Id',$question_set_Id);
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

      $sql = "INSERT INTO active_question_set
                              (class_id, question_set_Id)
                              VALUES (:class_id, :question_set_Id)";
      $stmt = $pdo->prepare($sql);
      include_once('isIdExistFunctions.php');
      $isClassIdExist = isClassIdExist($this->__get('class_id'));
      $isQuestionSetIdExist = isQuestionSetIdExist($this->__get('question_set_Id'));

      if($isClassIdExist){
        if($isQuestionSetIdExist){
          // classId and questionSetId exist; attempt to insert
          try{
            $stmt->execute(['class_id' => $this->class_id, 'question_set_Id' => $this->question_set_Id, ]);
          }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->class_id." ".$this->question_set_Id." ".$e->getMessage();
            $response["success"] = 0;
            return json_encode($response);
          }

          // success JSON response
          $response = array();
          $response["message"] = "Inserted: ".$this->class_id." ".$this->question_set_Id;
          $response["success"] = 1;
          return json_encode($response);
        }
        else{
          // build response for no question set id
          $response = array();
          $response["message"] = "ERROR INSERTING into active_question_set table: class_id ".$this->question_set_Id." does not exist";
          $response["success"] = 0;
          return json_encode($response);
        }
      }
      else{
        // build response for no class id
        $response = array();
        $response["message"] = "ERROR INSERTING into active_question_set table: student_id ".$this->class_id." does not exist";
        $response["success"] = 0;
        return json_encode($response);
      }


    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT class_id, question_set_Id
              FROM active_question_set
              WHERE class_id LIKE :class_id
                AND question_set_Id LIKE :question_set_Id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':class_id', $this->class_id);
      $stmt->bindValue(':question_set_Id', $this->question_set_Id);

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
      $response["message"] = "Success SELECTING from active_question_set";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }
  }
?>
