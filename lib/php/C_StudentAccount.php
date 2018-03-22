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

  class StudentAccount{
    private $student_id;
    private $first_name;
    private $last_name;
    private $salted_password;
    private $email;
    private $title;
    private $uniqueID;
    private $is_confirmed;


    function __construct($id, $fname, $lname, $spassword, $email, $title, $uniqueID, $is_confirmed) {
      $this->__set('student_id', $id);
      $this->__set('first_name',$fname);
      $this->__set('last_name', $lname);
      $this->__set('salted_password',$spassword);
      $this->__set('email',$email);
      $this->__set('title',$title);
      $this->__set('uniqueID',$uniqueID);
      $this->__set('is_confirmed',$is_confirmed);
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
                              (first_name, last_name, salted_password, email, title, uniqueID, is_confirmed)
                              VALUES (:first_name, :last_name, :salted_password, :email, :title, :uniqueID, :is_confirmed)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':first_name', $this->first_name);
      $stmt->bindValue(':last_name', $this->last_name);
      $stmt->bindValue(':salted_password', $this->salted_password);
      $stmt->bindValue(':email', $this->email);
      $stmt->bindValue(':title', $this->title);
      $stmt->bindValue(':uniqueID', $this->uniqueID);
      $stmt->bindValue(':is_confirmed', $this->is_confirmed);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->first_name." ".$this->last_name." ".$this->salted_password." ".
                                $this->email." ".$this->title." ".$this->uniqueID." ".
                                $this->is_confirmed." ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->first_name." ".$this->last_name." ".$this->salted_password." ".
                              $this->email." ".$this->title." ".$this->uniqueID." ".
                              $this->is_confirmed;
      $response["success"] = 1;
      $response["idInserted"] = $pdo->lastInsertId();
      return json_encode($response);
    }

    public function select(){
      include_once('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT student_id, first_name, last_name, salted_password, email, title, uniqueID, is_confirmed
              FROM student_account
              WHERE student_id LIKE :student_id
                AND first_name LIKE :first_name
                AND last_name LIKE :last_name
                AND salted_password LIKE :salted_password
                AND email LIKE :email
                AND title LIKE :title
                AND uniqueID LIKE :uniqueID
                AND is_confirmed LIKE :is_confirmed
                ";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':student_id', $this->student_id);
      $stmt->bindValue(':first_name', $this->first_name);
      $stmt->bindValue(':last_name', $this->last_name);
      $stmt->bindValue(':salted_password', $this->salted_password);
      $stmt->bindValue(':email', $this->email);
      $stmt->bindValue(':title', $this->title);
      $stmt->bindValue(':uniqueID', $this->uniqueID);
      $stmt->bindValue(':is_confirmed', $this->is_confirmed);

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