<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

  Example Usage:

  <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include('C_Question.php');
        $insertQuestion = new Question('Is this a question?', 'professor asked field', 'TrueOrFalseTag');
        $insertQuestion->insert();
      }
  ?>

  */

  class StudentAccount{
    private $student_id;
    private $first_name;
    private $last_name;
    private $salted_password;
    private $student_school_id;
    private $email;

    function __construct($id, $fn, $ln, $sp, $ssid, $em) {
      $this->__set('student_id', $id);
      $this->__set('first_name',$fn);
      $this->__set('last_name', $ln);
      $this->__set('salted_password',$sp);
      $this->__set('student_school_id',$ssid);
      $this->__set('email',$em);
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

      $sql = "INSERT INTO student_account
                              (first_name, last_name, salted_password, student_school_id, email)
                              VALUES (:first_name, :last_name, :salted_password, :student_school_id, :email)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':first_name', $this->first_name);
      $stmt->bindValue(':last_name', $this->last_name);
      $stmt->bindValue(':salted_password', $this->salted_password);
      $stmt->bindValue(':student_school_id', $this->student_school_id);
      $stmt->bindValue(':email', $this->email);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->first_name." ".$this->last_name." ".$this->salted_password." ".$this->student_school_id." ".$this->email.$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->first_name." ".$this->last_name." ".$this->salted_password." ".$this->student_school_id." ".$this->email;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      include_once('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT student_id, first_name, last_name, salted_password, student_school_id, email
              FROM student_account
              WHERE student_id LIKE :student_id
                AND first_name LIKE :first_name
                AND last_name LIKE :last_name
                AND salted_password LIKE :salted_password
                AND student_school_id LIKE :student_school_id
                AND email LIKE :email
                ";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':student_id', $this->student_id);
      $stmt->bindValue(':first_name', $this->first_name);
      $stmt->bindValue(':last_name', $this->last_name);
      $stmt->bindValue(':salted_password', $this->salted_password);
      $stmt->bindValue(':student_school_id', $this->student_school_id);
      $stmt->bindValue(':email', $this->email);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR SELECTING from Student Account: ".$e->getMessage();
        $response["success"] = 0;
        // echo json_encode($response);
        // die();
        return json_encode($response);
      }
      $pdo = null;
      $response = array();
      $response["message"] = "Success SELECTING from Student Account";
      $response["success"] = 1;
//      $response["what"] = empty($stmt->fetchAll(PDO::FETCH_ASSOC));
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);

      return json_encode($retVal);
    }

  }
?>
