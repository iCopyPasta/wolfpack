<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

  Example Usage:

  $insertQuestion = new Question('Is this a question?', 'teacher asked field', 'TrueOrFalseTag');
  $insertQuestion->insert();

  $selectQuestion = new Question('1', '%', '%', '%');
  $result = json_decode($selectQuestion->select(), true);

  */

  class Teaches{
    private $teacher_id;
    private $class_id;

    function __construct($teacherId,$classId) {
      $this->__set('teacher_id', $teacherId);
      $this->__set('class_id',$classId);
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

      $sql = "INSERT INTO teaches
                              (teacher_id, class_id)
                              VALUES (:teacher_id, :class_id)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['teacher_id' => $this->teacher_id, 'class_id' => $this->class_id]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->teacher_id." ".$this->class_id." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->teacher_id." ".$this->class_id;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    //JSON
    public function select(){
      
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT teacher_id, class_id
              FROM teaches
              WHERE teacher_id LIKE :teacher_id
                AND class_id LIKE :class_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':teacher_id', $this->teacher_id);
      $stmt->bindValue(':class_id', $this->class_id);

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
      $response["message"] = "Success SELECTING from Teaches";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }

  }
?>
