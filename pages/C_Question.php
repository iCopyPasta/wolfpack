<?php
  /*
  This class is used by the teacher to insert a new question into table "question".

  Example Usage:

  $insertQuestion = new Question('Is this a question?', 'professor asked field', 'TrueOrFalseTag');
  $insertQuestion->insert();

  $selectQuestion = new Question('1', '%', '%', '%');
  $result = json_decode($selectQuestion->select(), true);

  */

  class Question{
    private $question_id;
    private $description;
    private $asked;
    private $tags;
    private $potential_answers;
    private $correct_answers;

    function __construct($questionId, $description, $tags, $asked, $potentialAnswers, $correctAnswers) {
      $this->__set('question_id', $questionId);
      $this->__set('description',$description);
      $this->__set('tags',$tags);
      $this->__set('asked', $asked);
      $this->__set('potential_answers', $potentialAnswers);
      $this->__set('correct_answers', $correctAnswers);

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
                              (description, tags, asked, potential_answers, correct_answers)
                              VALUES (:description, :tags, :asked, :potential_answers, :correct_answers)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['description' => $this->description, 'tags' => $this->tags,
                        'asked' => $this->asked, 'potential_answers' => $this->potential_answers, 'correct_answers' => $this->correct_answers]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->description." ".$this->tags.
                                " ".$this->asked." ".$this->potential_answers." ".$this->correct_answers." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] =  "Inserted: ".$this->description." ".$this->tags.
                              " ".$this->asked." ".$this->potential_answers." ".$this->correct_answers;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_id, description, tags, asked, potential_answers, correct_answers
              FROM question
              WHERE question_id LIKE :question_id
                AND description LIKE :description
                AND asked LIKE :asked
                AND tags LIKE :tags
                AND potential_answers LIKE :potential_answers
                AND correct_answers LIKE :correct_answers";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_id', $this->question_id);
      $stmt->bindValue(':description', $this->description);
      $stmt->bindValue(':asked', $this->asked);
      $stmt->bindValue(':tags', $this->tags);
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
