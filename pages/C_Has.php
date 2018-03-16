<?php
  /*
  This class is used to insert and select from table "has".
  _______________
  |has           |
  |______________|
  |section_id    |
  |class_id      |
  |--------------|
  |______________|

  This table has a composite primary key built from the class_course and class_section table.
  For this reason, checks are made against those tables to ensure the class_id and section_id given exist in their respective tables.

  Example Usage:

  // insert a new "has"
    $aHas = new Has(1, 15);
    $response = $aHas->insert();

  // selecting a "has"; 0th element is success message; 1th to end are rows from table
    $aHas = new Has(1, 15);
    $response = $aHas->select();

  // to test for no rows existing
    $aHas = new Has(1, 15);
    $qJSON = json_decode($aHas->select(), true);
    if(array_key_exists(1, $qJSON)){
      echo 'exists<br>';
    }
    else{
      echo 'no exists<br>';
    }

*/

  class Has{
    private $class_id;
    private $section_id;

    function __construct($cid, $sid) {
      $this->__set('class_id',$cid);
      $this->__set('section_id',$sid);
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

    public function isClassIdExist($aClassId){
      // ensure that the 'class_id' exists in the class_course table before trying to insert
      include_once('/pages/C_ClassCourse.php');
      $course = new ClassCourse($aClassId, '%', '%', '%', '%');
      $qJSON = json_decode($course->select(), true);
      // if a row was returned then the class_id exists
      return array_key_exists(1, $qJSON);
    }

    public function isSectionIdExist($aSectionId){
      // ensure that the 'section_id' exists in the class_section table before trying to insert
      include_once('/pages/C_ClassSection.php');
      $section = new ClassSection($aSectionId, '%', '%', '%');
      $qJSON = json_decode($section->select(), true);
      // if a row was returned then the class_id exists
      return array_key_exists(1, $qJSON);
    }

    public function insert(){

      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "INSERT INTO has
                              (class_id, section_id)
                              VALUES (:class_id, :section_id)";
      $stmt = $pdo->prepare($sql);


      //TODO: maybe "isClassIdExist" and "isSectionExist" should be functions. Also, this code will be used a lot
      // ensure that the 'class_id' exists in the class_course table before trying to insert
      include_once('/pages/C_ClassCourse.php');
      $course = new ClassCourse($this->__get('class_id'), '%', '%', '%', '%');
      $qJSON = json_decode($course->select(), true);
      // if a row was returned then the class_id exists
      $isClassIdExist = array_key_exists(1, $qJSON);

      // ensure that the 'section_id' exists in the class_section table before trying to insert
      include_once('/pages/C_ClassSection.php');
      $section = new ClassSection($this->__get('section_id'), '%', '%', '%');
      $qJSON = json_decode($section->select(), true);
      // if a row was returned then the class_id exists
      $isSectionIdExist = array_key_exists(1, $qJSON);

      if($isClassIdExist){
        if($isSectionIdExist){
          // classId and sectionId exist; attempt to insert
          try{
            $stmt->execute(['class_id' => $this->class_id, 'section_id' => $this->section_id]);
          }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->class_id." ".$this->section_id." ".$e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
          }

          // success JSON response
          $response = array();
          $response["message"] = "Inserted: ".$this->class_id." ".$this->section_id;
          $response["success"] = 1;
          echo json_encode($response);

          $pdo = null;
        }
        else{
          // build response for no section id
          $response = array();
          $response["message"] = "ERROR INSERTING into Has table: section_id ".$this->section_id." does not exist in class_section table";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }
      else{
        // build response for no class id
        $response = array();
        $response["message"] = "ERROR INSERTING into Has table: class_id ".$this->class_id." does not exist in class_course table";
        $response["success"] = 0;
        echo json_encode($response);
      }


    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT class_id, section_id
              FROM has
              WHERE class_id LIKE :class_id
                AND section_id LIKE :section_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':class_id', $this->class_id);
      $stmt->bindValue(':section_id', $this->section_id);

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR SELECTING: ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      $pdo = null;
      $response = array();
      $response["message"] = "Success SELECTING from Has";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
