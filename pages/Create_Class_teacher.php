<?php
  /*
  This class is used by the teacher to insert a new class into table Class_Course.

  Example Usage:
  include('Create_Class_teacher.php');
  $insertClass = new ClassCourse(460, 'Olmstead', 'what is offering?');
  $insertClass->insert();
  */

  class ClassCourse{
    private $class_course_number;
    private $location;
    private $offering;

    function __construct($c,$l,$o) {
          $this->__set('class_course_number', $c);
          $this->__set('location',$l);
          $this->__set('offering',$o);
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

      $sql = "INSERT INTO class_course
                              (class_course_number, location, offering)
                              VALUES (:class_course_number, :location, :offering)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['class_course_number' => $this->class_course_number, 'location' => $this->location, 'offering' => $this->offering]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR on INSERT: " . $e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ";
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

  }
?>
