<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

   __________________
  |question_set      |
  |__________________|
  |question_set_id   |
  |------------------|
  |question_set_name |
  |teacher_id        |
  |__________________|



  Example Usage:

    include_once('C_QuestionSet.php');

  // insert a QuestionSet
    $questionSet = new QuestionSet('%', '1', 'testDeleteQuestionSet');
    $questionSet->insert();

  // select a QuestionSet
    $questionSet = new QuestionSet('1', '%', '%');
    $retVal = json_decode($questionSet->select());
    $question_set_id = $retVal[1]->question_set_id;
    $teacher_id = $retVal[1]->teacher_id;
    $question_set_name = $retVal[1]->question_set_name;

    echo $question_set_name;
    var_dump($retVal);

  // delete question set
    $questionSet = new QuestionSet('%', '1', 'testDeleteQuestionSet');
    $questionSet->delete());


  // update a QuestionSet
    $questionSet = new QuestionSet('%', '1', 'testUpdateQuestionSet');
    $questionSet->insert();
    $aNewQuestionSetName = "i'm changing the question set name";
    $questionSet->update('1', $aNewQuestionSetName);


  */
include_once('isIdExistFunctions.php');

  class QuestionSet{
    private $question_set_id;
    private $teacher_id;
    private $question_set_name;

    function __construct($question_set_id, $teacher_id,  $question_set_name) {
      $this->__set('question_set_id', $question_set_id);
      $this->__set('teacher_id', $teacher_id);
      $this->__set('question_set_name',$question_set_name);
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
      include_once('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "INSERT INTO question_set
                              (teacher_id, question_set_name)
                              VALUES (:teacher_id, :question_set_name)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':teacher_id', $this->teacher_id);
      $stmt->bindValue(':question_set_name', $this->question_set_name);

      // does the teacher_id exist?
      if(!isTeacherIdExist($this->teacher_id)){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: teacher_id ".$this->teacher_id." does not exist: ";
        $response["success"] = 0;
        return json_encode($response);
      }

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->teacher_id." ".$this->question_set_name." ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->teacher_id." ".$this->question_set_name;
      $response["success"] = 1;
      $response["idInserted"] = $pdo->lastInsertId();
      return json_encode($response);
    }

    public function select(){
      include_once('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_set_id, teacher_id, question_set_name
              FROM question_set
              WHERE question_set_id LIKE :question_set_id
                AND teacher_id LIKE :teacher_id
                AND question_set_name LIKE :question_set_name";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_set_id', $this->question_set_id);
      $stmt->bindValue(':teacher_id', $this->teacher_id);
      $stmt->bindValue(':question_set_name', $this->question_set_name);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR SELECTING from question_set: ".$e->getMessage();
        $response["success"] = 0;
        // echo json_encode($response);
        // die();
        return json_encode($response);
      }
      $pdo = null;
      $response = array();
      $response["message"] = "Success SELECTING from question_set";
      $response["success"] = 1;
//      $response["what"] = empty($stmt->fetchAll(PDO::FETCH_ASSOC));
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);

      return json_encode($retVal);
    }

    public function delete(){
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "DELETE
              FROM question_set
              WHERE question_set_id LIKE :question_set_id
                AND question_set_name LIKE :question_set_name
                AND teacher_id LIKE :teacher_id
                ";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_set_id', $this->__get('question_set_id'));
      $stmt->bindValue(':question_set_name', $this->__get('question_set_name'));
      $stmt->bindValue(':teacher_id', $this->__get('teacher_id'));

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR DELETING: ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      $pdo = null;
      $response = array();
      $response["message"] = "Success DELETING from Question";
      $response["success"] = 1;
      $response["rowCount"] = $stmt->rowCount();
      return json_encode($response);
    }

    public function update($newTeacherId, $newQuestionSetName){
//      __________________
//      |question_set      |
//      |__________________|
//      |question_set_id   |
//      |------------------|
//      |question_set_name |
//      |teacher_id        |
//      |__________________|
      $connection = new Connection;
      $pdo = $connection->getConnection();
      $sql = "UPDATE question_set
              SET question_set_name = :newQuestionSetName,
                  teacher_id = :newTeacherId
              WHERE question_set_id LIKE :question_set_id
                AND question_set_name LIKE :question_set_name
                AND teacher_id LIKE :teacher_id
                ";

      // does the teacher_id exist?
//      include_once('isIdExistFunctions.php');
      if(!isTeacherIdExist($this->__get('teacher_id'))){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR UPDATING: teacher_id ".$this->teacher_id." does not exist: ";
        $response["success"] = 0;
        return json_encode($response);
      }

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(':newQuestionSetName', $newQuestionSetName);
      $stmt->bindValue(':newTeacherId', $newTeacherId);

      $stmt->bindValue(':question_set_id', $this->__get('question_set_id'));
      $stmt->bindValue(':question_set_name', $this->__get('question_set_name'));
      $stmt->bindValue(':teacher_id', $this->__get('teacher_id'));

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR UPDATING ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Update successful";
      $response["success"] = 1;
      $response["rowCount"] = $stmt->rowCount();
      return json_encode($response);

    }

  }
?>
