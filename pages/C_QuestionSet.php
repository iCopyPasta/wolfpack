<?php
  /*
  This class is used by the user to:
    insert a new student account into table "student_account"
    select from "student_account"

  Example usage (see Sign_up.php for more):

    include_once('C_StudentAccount.php');

    $selectStudentAccount = new StudentAccount('thisValueIsIgnored', 'Scott', 'Wilson', $hashPassword, , $insertEmail);
    $qJSON = json_decode($selectStudentAccount->insert(), true);
    $success = isset($qJSON[0]['success']) ? $qJSON[0]['success'] : null;
    $message = isset($qJSON[0]['message']) ? $qJSON[0]['message'] : null;


    $selectStudentAccount = new StudentAccount('%','%','%','%', $insertEmail);
    $qJSON = json_decode($selectStudentAccount->select(), true);
    $success = isset($qJSON[0]['success']) ? $qJSON[0]['success'] : null;
    $message = isset($qJSON[0]['message']) ? $qJSON[0]['message'] : null;
    $email = isset($qJSON[1]['email']) ? $qJSON[1]['email'] : null;
    $firstName = isset($qJSON[1]['first_name']) ? $qJSON[1]['first_name'] : null;

  */

  class QuestionSet{
    private $question_set_id;
    private $question_set_name;

    function __construct($question_set_id, $question_set_name) {
      $this->__set('question_set_id', $question_set_id);
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
                              (question_set_name)
                              VALUES (:question_set_name)";
      $stmt = $pdo->prepare($sql);
//      $stmt->bindValue(':question_set_id', $this->question_set_id);
      $stmt->bindValue(':question_set_name', $this->question_set_name);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->question_set_name." ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->question_set_name;
      $response["success"] = 1;
      return json_encode($response);
    }

    public function select(){
      include_once('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_set_id, question_set_name
              FROM question_set
              WHERE question_set_id LIKE :question_set_id
                AND question_set_name LIKE :question_set_name";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_set_id', $this->question_set_id);
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

  }
?>
