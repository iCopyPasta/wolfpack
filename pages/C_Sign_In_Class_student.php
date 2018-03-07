<?php
  /*
  This class is used by the student to sign in.

  Example Usage from "Sign_in_student.php":

  <?php
      $alertString="";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $insertEmail=$_POST["inputEmail"];
        $insertPass=$_POST["inputPassword"];

        include('C_Sign_In_Class_Student.php');
        $studentSignIn = new StudentSignIn($insertEmail, $insertPass);

        // true if email+pw found; false if no record found
        if(json_decode($studentSignIn->select())->success){
          $alertString="";
          header("Location: logged_in_student.php");  //if the query was successful
          die();
        }
        else{
          $alertString='<div class="alert alert-danger">
                <strong>Error.</strong> Username or password does not exist.
                </div>';
        }
      }
    ?>

  */

  class StudentSignIn{
    private $email;
    private $password;

    function __construct($e,$p) {
          $this->__set('email', $e);
          $this->__set('password',$p);
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

    public function select(){
      // may be better to make connection in calling page
      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT email, salted_password
              FROM student_account
              WHERE email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':email', $this->email);
      
      try{
        // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR SELECTING: ".$this->email." ".$this->password." ".$e->getMessage();
        $response["success"] = 0;
        // echo json_encode($response);
        // die();
        return json_encode($response);
      }

      if($stmt){  // a value was returned, may be a row or empty
        // email and pw match
        if(!empty($stmt->fetch())){  //if its not empty then email and pw match
          $response = array();
          $response["message"] = "Match: Email+PW: ";
          $response["success"] = 1;
          return json_encode($response);
        }
        // email and pw do NOT match
        else{
          $response = array();
          $response["message"] = "No Match: Email+PW: ";
          $response["success"] = 0;
          return json_encode($response);
        }
      }

      $pdo = null;
    }
  }
?>
