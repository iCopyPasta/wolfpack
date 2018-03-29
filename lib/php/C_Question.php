<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

   __________________
  |question          |
  |__________________|
  |question_id       |
  |------------------|
  |teacher_id        |
  |question_type     |
  |description       |
  |potential_answers |
  |correct_answers   |
  |__________________|



  Example Usage:

  // insert a Question
    $question = new Question('%', '1', 'testDeleteQuestion2', 'testDeleteQuestion2', 'none', 'none');
    echo $question->insert();


  // select a Question
    $question = new Question('1', '%', '%', '%', '%', '%');
    $retVal = json_decode($question->select());
    $question_id = $retVal[1]->question_id;
    $teacher_id = $retVal[1]->teacher_id;
    $question_type = $retVal[1]->question_type;
    $description = $retVal[1]->description;
    $potential_answers = $retVal[1]->potential_answers;
    $correct_answers = $retVal[1]->correct_answers;


  // delete a Question
    $question = new Question('%', '1', 'testDeleteQuestion2', 'testDeleteQuestion2', 'none', 'none');
    echo $question->insert();

    $question = new Question('%', '1', 'testDeleteQuestion2', 'testDeleteQuestion2', 'none', 'none');
    echo $question->delete();

  // update a Question
    $question = new Question('2020', '%', '%', '%', '%', '%');
    $retVal = json_decode($question->select());
    $question_id = $retVal[1]->question_id;
    $teacher_id = $retVal[1]->teacher_id;
    $question_type = $retVal[1]->question_type;
    $description = $retVal[1]->description;
    $potential_answers = $retVal[1]->potential_answers;
    $correct_answers = $retVal[1]->correct_answers;

    $description = "i am only changing the description";

    $retVal = json_decode($question->update($teacher_id, $description, $question_type, $potential_answers, $correct_answers));

  */
  

 include_once('isIdExistFunctions.php');


  class Question{
    private $question_id;
    private $teacher_id;
    private $description;
    private $question_type;
    private $potential_answers;
    private $correct_answers;

    function __construct($qId, $teacher_id, $description, $question_type, $potential_answers, $correct_answers) {
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
//      include_once('isIdExistFunctions.php');
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

    public function delete(){
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "DELETE
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

    public function update($newTeacherId, $newDescription, $newQuestionType, $newPotentialAnswers, $newCorrectAnswers){
    //__________________
    //|question          |
    //|__________________|
    //|question_id       |
    //|------------------|
    //|teacher_id        |
    //|question_type     |
    //|description       |
    //|potential_answers |
    //|correct_answers   |
    //|__________________|
      $connection = new Connection;
      $pdo = $connection->getConnection();
      $sql = "UPDATE question
              SET teacher_id = :newTeacherId,
                  question_type = :newQuestionType,
                  description = :newDescription,
                  potential_answers = :newPotentialAnswers,
                  correct_answers = :newCorrectAnswers
              WHERE question_id LIKE :question_id
                AND teacher_id LIKE :teacher_id
                AND question_type LIKE :question_type
                AND description LIKE :description
                AND potential_answers = :potential_answers
                AND correct_answers = :correct_answers
                ";

      // does the teacher_id exist?
//      include_once('isIdExistFunctions.php');
      if(!isTeacherIdExist($this->teacher_id)){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR UPDATING: teacher_id ".$this->teacher_id." does not exist: ";
        $response["success"] = 0;
        return json_encode($response);
      }

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(':newTeacherId', $newTeacherId);
      $stmt->bindValue(':newQuestionType', $newQuestionType);
      $stmt->bindValue(':newDescription', $newDescription);
      $stmt->bindValue(':newPotentialAnswers', $newPotentialAnswers);
      $stmt->bindValue(':newCorrectAnswers', $newCorrectAnswers);

      $stmt->bindValue(':question_id', $this->__get('question_id'));
      $stmt->bindValue(':teacher_id', $this->__get('teacher_id'));
      $stmt->bindValue(':question_type', $this->__get('question_type'));
      $stmt->bindValue(':description', $this->__get('description'));
      $stmt->bindValue(':potential_answers', $this->__get('potential_answers'));
      $stmt->bindValue(':correct_answers', $this->__get('correct_answers'));

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

    //not sure why we need to replace; i'm going to implement update instead
    //TODO: implement replace
    //TODO: test replace
    //reference: http://download.nust.na/pub6/mysql/doc/refman/5.1/en/replace.html
//    public function replace(){
//      $connection = new Connection;
//      $pdo = $connection->getConnection();
//
//      $sql = "REPLACE
//              INTO question
//                (teacher_id, question_type, description, potential_answers, correct_answers)
//                VALUES (:teacher_id, :question_type, :description, :potential_answers, :correct_answers)";
//      $stmt = $pdo->prepare($sql);
//
//      // does the teacher_id exist?
////      include_once('isIdExistFunctions.php');
//      if(!isTeacherIdExist($this->teacher_id)){
//        // fail JSON response
//        $response = array();
//        $response["message"] = "ERROR INSERTING: teacher_id ".$this->teacher_id." does not exist: ";
//        $response["success"] = 0;
//        return json_encode($response);
//      }
//
//      try{
//        $stmt->execute(['teacher_id' => $this->teacher_id,
//                'description' => $this->description,
//                'question_type' => $this->question_type,
//                'potential_answers' => $this->potential_answers,
//                'correct_answers' => $this->correct_answers]);
//      }catch (Exception $e){
//        // fail JSON response
//        $response = array();
//        $response["message"] = "ERROR INSERTING: ".$this->question_type." ".$this->description." ".$e->getMessage();
//        $response["success"] = 0;
//        return json_encode($response);
//      }
//
//      // success JSON response
//      $response = array();
//      $response["message"] = "Inserted: ".$this->question_type." ".$this->description;
//      $response["success"] = 1;
//      $response["idInserted"] = $pdo->lastInsertId();
//      return json_encode($response);
//    }

  }
?>
