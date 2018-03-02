<?php
  /*
  This class is used by the teacher to insert a new class into table Class_Course.

  Example Usage:

  <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include('Create_Question_teacher.php');
        $insertClass = new ClassCourse('Is this a question?', 'professor asked field', 'TrueOrFalseTag');
        $insertClass->insert();
      }
  ?>

  */

  class Question{
    private $description;
    private $professor_asked;
    private $tags;

    function __construct($d,$p,$t) {
          $this->__set('description', $d);
          $this->__set('professor_asked',$p);
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
      include('Connection.php');
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
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->description." ".$this->professor_asked." ".$this->tags;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

  }
?>
