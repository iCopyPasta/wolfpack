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

  class Question{
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

    public function insert(){
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "INSERT INTO question
                              (description, professor_asked, tags)
                              VALUES (:description, :professor_asked, :tags)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['description' => $this->description, 'professor_asked' => $this->professor_asked, 'tags' => $this->tags]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->description." ".$this->professor_asked." ".$this->tags." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->description." ".$this->professor_asked." ".$this->tags;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_id, description, professor_asked, tags
              FROM question
              WHERE question_id LIKE :question_id
                AND description LIKE :description
                AND professor_asked LIKE :professor_asked
                AND tags LIKE :tags";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_id', $this->question_id);
      $stmt->bindValue(':description', $this->description);
      $stmt->bindValue(':professor_asked', $this->professor_asked);
      $stmt->bindValue(':tags', $this->tags);

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
      return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

  }
?>
