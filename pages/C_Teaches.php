<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

  Example Usage:

  $insertQuestion = new Question('Is this a question?', 'professor asked field', 'TrueOrFalseTag');
  $insertQuestion->insert();

  $selectQuestion = new Question('1', '%', '%', '%');
  $result = json_decode($selectQuestion->select(), true);

  */

  class Teaches{
    private $professor_id;
    private $section_id;

    function __construct($pid,$sid) {
      $this->__set('professor_id', $pid);
      $this->__set('section_id',$sid);
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
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "INSERT INTO teaches
                              (professor_id, section_id)
                              VALUES (:professor_id, :section_id)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['professor_id' => $this->professor_id, 'section_id' => $this->section_id]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->professor_id." ".$this->section_id." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->professor_id." ".$this->section_id;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT professor_id, section_id
              FROM teaches
              WHERE professor_id LIKE :professor_id
                AND section_id LIKE :section_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':professor_id', $this->professor_id);
      $stmt->bindValue(':section_id', $this->section_id);

      try{
        // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
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
      $response["message"] = "Success SELECTING from Teaches";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }

  }
?>
