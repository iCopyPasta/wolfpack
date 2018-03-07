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

  class ViewQuestion{
    private $question_id;
    private $description;
    private $professor_asked;
    private $tags;

    function __construct($q,$d,$p,$t) {
          $this->__set('question_id', $q);
          $this->__set('description',$d);
          $this->__set('professor_asked', $p);
          $this->__set('tags',$t);
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

      $sql = "SELECT question_id, description, professor_asked, tags
              FROM question
              WHERE question_id LIKE :question_id
                AND description LIKE :description
                AND professor_asked LIKE :professor_asked
                AND tags LIKE :tags";

      $stmt = $pdo->prepare($sql);
      // $stmt->bindValue(':question_id', $this->question_id);
      // $stmt->bindValue(':description', $this->description);
      // $stmt->bindValue(':professor_asked', $this->professor_asked);
      // $stmt->bindValue(':tags', $this->tags);

      try{
        // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
        $stmt->execute();
        // echo json_encode($stmt->fetchAll());
        // $stmt->query();
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
      return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
      // if($stmt){  // a value was returned, may be a row or empty
        // email and pw match
        // if(!empty($stmt->fetch())){  //if its not empty then email and pw match
        //   $response = array();
        //   $response["message"] = "Match";
        //   $response["success"] = 1;
        //   return json_encode($response);
        // }
        // // email and pw do NOT match
        // else{
        //   $response = array();
        //   $response["message"] = "No Match";
        //   $response["success"] = 0;
        //   return json_encode($response);
        // }
      // }


    }
  }
?>
