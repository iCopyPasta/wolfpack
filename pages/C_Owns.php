<?php
  /*
  This class is used to insert and select from table "owns".
  _______________
  |owns          |
  |______________|
  |professor_id  |
  |question_id   |
  |--------------|
  |              |
  |______________|

  This table has a composite primary key built from the question and professor_account table.
  For this reason, checks are made against those tables to ensure the professor_id and question_id given exist in their respective tables.

  Example Usage:

  // insert a new "owns"
    $aOwns = new Owns(1, 1);
    $response = $aOwns->insert();

  // selecting a "owns"; 0th element is success message; 1th to end are rows from table
    $aOwns = new IsIn(1, 1);
    $response = $aOwns->select();

  // to test for no rows existing
    $aOwns = new Owns(1, 15);
    $qJSON = json_decode($aOwns->select(), true);
    if(array_key_exists(1, $qJSON)){
      echo 'exists<br>';
    }
    else{
      echo 'no exists<br>';
    }

*/

  class Owns{
    private $professor_id;
    private $question_id;

    function __construct($profId, $questionId) {
      $this->__set('professor_id',$profId);
      $this->__set('question_id',$questionId);
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

      $sql = "INSERT INTO owns
                              (professor_id, question_id)
                              VALUES (:professor_id, :question_id)";
      $stmt = $pdo->prepare($sql);


      //TODO: maybe "isStudentIdExist" and "isCourseExist" should be functions. Also, this code will be used a lot
      // ensure that the 'student_id' exists in the student_account table before trying to insert
      include_once('/pages/C_Question.php');
      $question = new Question($this->__get('question_id'), '%', '%', '%', '%', '%');
      $qJSON = json_decode($question->select(), true);
      // if a row was returned then the student_id exists
      $isQuestionIdExist = array_key_exists(1, $qJSON);

      // ensure that the 'class_id' exists in the class_course table before trying to insert
      include_once('/pages/C_ProfessorAccount.php');
      $professor = new ProfessorAccount($this->__get('professor_id'), '%', '%', '%', '%', '%', '%', '%', '%');
      $qJSON = json_decode($professor->select(), true);
      // if a row was returned then the class_id exists
      $isProfessorIdExist = array_key_exists(1, $qJSON);

      if($isQuestionIdExist){
        if($isProfessorIdExist){
          // professorID and questionID exist; attempt to insert
          try{
            $stmt->execute(['professor_id' => $this->professor_id, 'question_id' => $this->question_id]);
          }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->professor_id." ".$this->question_id." ".$e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
          }

          // success JSON response
          $response = array();
          $response["message"] = "Inserted: ".$this->professor_id." ".$this->question_id;
          $response["success"] = 1;
          echo json_encode($response);

          $pdo = null;
        }
        else{
          // build response for no professor id
          $response = array();
          $response["message"] = "ERROR INSERTING into owns table: professor_id ".$this->professor." does not exist in owns table";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }
      else{
        // build response for no question id
        $response = array();
        $response["message"] = "ERROR INSERTING into owns table: question_id ".$this->question_id." does not exist in owns table";
        $response["success"] = 0;
        echo json_encode($response);
      }


    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT professor_id, question_id
              FROM owns
              WHERE professor_id LIKE :professor_id
                AND question_id LIKE :question_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':professor_id', $this->professor_id);
      $stmt->bindValue(':question_id', $this->question_id);

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
      $response["message"] = "Success SELECTING from owns";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
