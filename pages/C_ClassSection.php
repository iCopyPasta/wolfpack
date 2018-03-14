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

  class ClassSection{
    private $section_id;
    private $class_section_number;
    private $location;
    private $offering;

    function __construct($id, $classSectionNumber, $location, $offering) {
      $this->__set('section_id',$id);
      $this->__set('class_section_number', $classSectionNumber);
      $this->__set('location', $location);
      $this->__set('offering', $offering);
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

      $sql = "INSERT INTO class_section
                              (class_section_number, location, offering)
                              VALUES (:class_section_number, :location, :offering)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['class_section_number' => $this->class_section_number, 'location' => $this->location, 'offering' => $this->offering]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->class_section_number." ".$this->location." ".$this->offering." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->class_section_number." ".$this->location." ".$this->offering;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT section_id, class_section_number, location, offering
              FROM class_section
              WHERE section_id LIKE :section_id
                AND class_section_number LIKE :class_section_number
                AND location LIKE :location
                AND offering LIKE :offering";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':section_id', $this->section_id);
      $stmt->bindValue(':class_section_number', $this->class_section_number);
      $stmt->bindValue(':location', $this->location);
      $stmt->bindValue(':offering', $this->offering);

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
      $response["message"] = "Success SELECTING from ClassSection";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
