<?php
  /*
  This class is used by the teacher to insert or select table "class_section".

  Example Usage:

  // insert a new section
  $aSection = new ClassSection('aaa', 'bbbb', 'cccc');
  $aSection->insert();

  // selecting a section; 0th element is success message; 1th to end are rows from table
  $aSection = new ClassSection('$', 'aaa', 'bbbb', 'cccc');
  $myRows = $aSection->select();

  */

  class ClassCourseSection{
    private $class_id;
    private $title;
    private $description;
//    private $section_number;
    private $offering;
    private $location;

    function __construct($classId, $title, $desc, $offering, $location){
      $this->__set('class_id', $classId);
      $this->__set('title', $title);
      $this->__set('description', $desc);
      $this->__set('offering', $offering);
      $this->__set('location', $location);
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

      $sql = "INSERT INTO class_course_section
                              (title, description, offering, location)
                              VALUES (:title, :description, :offering, :location)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['title'=>$this->title,'description'=>$this->description, 'offering' => $this->offering, 'location' => $this->location]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->title." ".$this->description." ".$this->offering." ".$this->location." ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->title." ".$this->description." ".$this->offering." ".$this->location;
      $response["success"] = 1;
      $response["idInserted"] = $pdo->lastInsertId();
      return json_encode($response);
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT class_id, title, description, offering, location
              FROM class_course_section
              WHERE class_id LIKE :class_id
                AND title LIKE :title
                AND description LIKE :description
                AND location LIKE :location
                AND offering LIKE :offering";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':class_id', $this->class_id);
      $stmt->bindValue(':title', $this->title);
      $stmt->bindValue(':description', $this->description);
      $stmt->bindValue(':offering', $this->offering);
      $stmt->bindValue(':location', $this->location);

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
      $response["message"] = "Success SELECTING from Class";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
