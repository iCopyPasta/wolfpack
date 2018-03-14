<?php
  /*
  This class is used by the teacher to insert or select table "class_course".

  Example Usage:

  // insert a new course
  $aCourse = new ClassCourse('thisWontMatterSinceItsThePK', '460', 'Olmstead 218', 'whatIsOffering?');
  $aCourse->insert();

  // selecting a course; 0th element is success message; 1th to end are rows from table
  $aCourse = new ClassCourse('1', '460', '%', '%');
  $myRows = $aCourse->select();

*/

  class ClassCourse{
    private $class_id;
    private $title;
    private $description;

    function __construct($id, $title, $description) {
      $this->__set('class_id',$id);
      $this->__set('title',$title);
      $this->__set('description',$description);
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

      $sql = "INSERT INTO class_course
                              (title, description)
                              VALUES (:title, :description)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['title' => $this->title,
                        'description' => $this->description]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->title." ".$this->description." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->title." ".$this->description;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT class_id, title, description
              FROM class_course
              WHERE class_id LIKE :class_id
                AND title LIKE :title
                AND description LIKE :description";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':class_id', $this->class_id);
      $stmt->bindValue(':title', $this->title);
      $stmt->bindValue(':description', $this->description);

      try{
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
      $response["message"] = "Success SELECTING from ClassCourse";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
