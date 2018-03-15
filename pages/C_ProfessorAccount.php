<?php
/*
This class is used by the user to:
  insert a new professor account into table "professor_account"
  select from "professor_account"

Example usage (see Sign_up.php for more):

  include_once('C_ProfessorAccount.php');

  $selectStudentAccount = new StudentAccount('thisValueIsIgnored', 'Scott', 'Wilson', $hashPassword, 'studentSchoolID', $insertEmail);
  $qJSON = json_decode($selectStudentAccount->insert(), true);
  $success = isset($qJSON[0]['success']) ? $qJSON[0]['success'] : null;
  $message = isset($qJSON[0]['message']) ? $qJSON[0]['message'] : null;


  $selectStudentAccount = new StudentAccount('%','%','%','%', '%', $insertEmail);
  $qJSON = json_decode($selectStudentAccount->select(), true);
  $success = isset($qJSON[0]['success']) ? $qJSON[0]['success'] : null;
  $message = isset($qJSON[0]['message']) ? $qJSON[0]['message'] : null;
  $email = isset($qJSON[1]['email']) ? $qJSON[1]['email'] : null;
  $firstName = isset($qJSON[1]['first_name']) ? $qJSON[1]['first_name'] : null;

*/

class ProfessorAccount{
    private $professor_id;
    private $first_name;
    private $last_name;
    private $salted_password;
    private $professor_school_id;
    private $email;
    private $title;
    private $uniqueID;
    private $isConfirmed;


    function __construct($id, $fname, $lname, $spassword, $profSchoolid, $email, $title, $uniqueID, $isConfirmed) {
        $this->__set('professor_id', $id);
        $this->__set('first_name',$fname);
        $this->__set('last_name', $lname);
        $this->__set('salted_password',$spassword);
        $this->__set('professor_school_id',$profSchoolid);
        $this->__set('email',$email);
        $this->__set('title',$title);
        $this->__set('uniqueID',$uniqueID);
        $this->__set('isConfirmed',$isConfirmed);
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

        $sql = "INSERT INTO professor_account
                              (first_name, last_name, salted_password, professor_school_id, email, title, uniqueID, isConfirmed)
                              VALUES (:first_name, :last_name, :salted_password, :professor_school_id, :email, :title, :uniqueID, :isConfirmed)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':first_name', $this->first_name);
        $stmt->bindValue(':last_name', $this->last_name);
        $stmt->bindValue(':salted_password', $this->salted_password);
        $stmt->bindValue(':professor_school_id', $this->professor_school_id);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':uniqueID', $this->uniqueID);
        $stmt->bindValue(':isConfirmed', $this->isConfirmed);

        try{
            $stmt->execute();
        }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->first_name." ".$this->last_name." ".$this->salted_password." ".
                $this->professor_school_id." ".$this->email." ".$this->title." ".$this->uniqueID." ".
                $this->isConfirmed." ".$e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
        }

        // success JSON response
        $response = array();
        $response["message"] = "Inserted: ".$this->first_name." ".$this->last_name." ".$this->salted_password." ".
            $this->professor_school_id." ".$this->email." ".$this->title." ".$this->uniqueID." ".
            $this->isConfirmed;
        $response["success"] = 1;
        echo json_encode($response);

        $pdo = null;
    }

    public function select(){
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();

        $sql = "SELECT professor_id, first_name, last_name, salted_password, professor_school_id, email, title, uniqueID, isConfirmed
              FROM professor_account
              WHERE professor_id LIKE :professor_id
                AND first_name LIKE :first_name
                AND last_name LIKE :last_name
                AND salted_password LIKE :salted_password
                AND professor_school_id LIKE :professor_school_id
                AND email LIKE :email
                AND title LIKE :title
                AND uniqueID LIKE :uniqueID
                AND isConfirmed LIKE :isConfirmed
                ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':professor_id', $this->professor_id);
        $stmt->bindValue(':first_name', $this->first_name);
        $stmt->bindValue(':last_name', $this->last_name);
        $stmt->bindValue(':salted_password', $this->salted_password);
        $stmt->bindValue(':professor_school_id', $this->professor_school_id);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':uniqueID', $this->uniqueID);
        $stmt->bindValue(':isConfirmed', $this->isConfirmed);

        try{
            $stmt->execute();
        }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR SELECTING from professor Account: ".$e->getMessage();
            $response["success"] = 0;
            // echo json_encode($response);
            // die();
            return json_encode($response);
        }
        $pdo = null;
        $response = array();
        $response["message"] = "Success SELECTING from Professor Account";
        $response["success"] = 1;
//      $response["what"] = empty($stmt->fetchAll(PDO::FETCH_ASSOC));
        $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
        array_unshift($retVal, $response);

        return json_encode($retVal);
    }

}
?>
