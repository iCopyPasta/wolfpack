<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

  Example Usage:

  $insertQuestion = new Question('Is this a question?', 'teacher asked field', 'TrueOrFalseTag');
  $insertQuestion->insert();

  $selectQuestion = new Question('1', '%', '%', '%');
  $result = json_decode($selectQuestion->select(), true);

  */
  
  //why was this commented out?
  include('isIdExistFunctions.php');

  class Question{
    private $question_id;
    private $teacher_id;
    private $description;
    private $question_type;
    private $potential_answers;
    private $correct_answers;

    function __construct($qId, $teacher_id, $description,$question_type,$potential_answers,$correct_answers) {
      $this->__set('teacher_id', $teacher_id);
      $this->__set('question_type', $question_type);
      $this->__set('question_id', $qId);
      $this->__set('description',$description);
      $this->__set('potential_answers', $potential_answers);
      $this->__set('correct_answers', $correct_answers);
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

      $sql = "INSERT INTO question
                              (teacher_id, question_type, description, potential_answers, correct_answers)
                              VALUES (:teacher_id, :question_type, :description, :potential_answers, :correct_answers)";
      $stmt = $pdo->prepare($sql);

      // does the teacher_id exist?
      if(!isTeacherIdExist($this->teacher_id)){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: teacher_id ".$this->teacher_id." does not exist: ";
        $response["success"] = 0;
        return json_encode($response);
      }

      try{
        $stmt->execute(['teacher_id' => $this->teacher_id,
                        'description' => $this->description,
                        'question_type' => $this->question_type,
                        'potential_answers' => $this->potential_answers,
                        'correct_answers' => $this->correct_answers]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->question_type." ".$this->description." ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->question_type." ".$this->description;
      $response["success"] = 1;
      $response["idInserted"] = $pdo->lastInsertId();
      return json_encode($response);
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_id, teacher_id, question_type, description, potential_answers, correct_answers
              FROM question
              WHERE question_id LIKE :question_id
                AND teacher_id LIKE :teacher_id
                AND question_type LIKE :question_type 
                AND description LIKE :description
                AND potential_answers LIKE :potential_answers
                AND correct_answers LIKE :correct_answers";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':teacher_id', $this->teacher_id);
      $stmt->bindValue(':question_type', $this->question_type);
      $stmt->bindValue(':question_id', $this->question_id);
      $stmt->bindValue(':description', $this->description);
      $stmt->bindValue(':potential_answers', $this->potential_answers);
      $stmt->bindValue(':correct_answers', $this->correct_answers);

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
      $response["message"] = "Success SELECTING from Question";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }

  }
?>
