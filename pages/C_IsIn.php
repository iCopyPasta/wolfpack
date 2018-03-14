<?php
  /*
  This class is used to insert and select from table "is_in".
  _______________
  |is_in         |
  |______________|
  |student_id    |
  |class_id      |
  |--------------|
  |section_id    |
  |______________|

  This table has a composite primary key built from the student_account and class_course table.
  For this reason, checks are made against those tables to ensure the student_id and class_id given exist in their respective tables.
  Although section_id is not part of the key, it is a pk in class_section table therefore a check is performed on that attribute as well.

  Example Usage:

  // insert a new "is_in"
    $aIsIn = new IsIn(11, 1, 1);
    $response = $aIsIn->insert();

  // selecting a "is_in"; 0th element is success message; 1th to end are rows from table
    $aIsIn = new IsIn(1, 15);
    $response = $aIsIn->select();

  // to test for no rows existing
    $aIsIn = new IsIn(1, 15);
    $qJSON = json_decode($aIsIn->select(), true);
    if(array_key_exists(1, $qJSON)){
      echo 'exists<br>';
    }
    else{
      echo 'no exists<br>';
    }

*/

  class IsIn{
    private $student_id;
    private $class_id;
    private $section_id;

    function __construct($stud_id, $cid, $section_id) {
      $this->__set('student_id',$stud_id);
      $this->__set('class_id',$cid);
      $this->__set('section_id',$section_id);
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

      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "INSERT INTO is_in
                              (student_id, class_id, section_id)
                              VALUES (:student_id, :class_id, :section_id)";
      $stmt = $pdo->prepare($sql);


      //TODO: maybe "isStudentIdExist" and "isCourseExist" should be functions. Also, this code will be used a lot
      // ensure that the 'student_id' exists in the student_account table before trying to insert
      include_once('/pages/C_StudentAccount.php');
      $student = new StudentAccount($this->__get('student_id'), '%', '%', '%', '%', '%');
      $qJSON = json_decode($student->select(), true);
      // if a row was returned then the student_id exists
      $isStudentIdExist = array_key_exists(1, $qJSON);

      // ensure that the 'class_id' exists in the class_course table before trying to insert
      include_once('/pages/C_ClassCourse.php');
      $course = new ClassCourse($this->__get('class_id'), '%', '%', '%', '%');
      $qJSON = json_decode($course->select(), true);
      // if a row was returned then the class_id exists
      $isClassIdExist = array_key_exists(1, $qJSON);

      // ensure that the 'section_id' exists in the class_section table before trying to insert
      include_once('/pages/C_ClassSection.php');
      $section = new ClassSection($this->__get('class_id'), '%', '%', '%');
      $qJSON = json_decode($section->select(), true);
      // if a row was returned then the section_id exists
      $isSectionIdExist = array_key_exists(1, $qJSON);

      if($isStudentIdExist){
        if($isClassIdExist){
          if($isSectionIdExist){
            // classId and sectionId exist; attempt to insert
            try{
              $stmt->execute(['student_id' => $this->student_id, 'class_id' => $this->class_id, 'section_id' => $this->section_id]);
            }catch (Exception $e){
              // fail JSON response
              $response = array();
              $response["message"] = "ERROR INSERTING: ".$this->student_id." ".$this->class_id." ".$this->section_id.$e->getMessage();
              $response["success"] = 0;
              echo json_encode($response);
              die();
            }

            // success JSON response
            $response = array();
            $response["message"] = "Inserted: ".$this->student_id." ".$this->class_id." ".$this->section_id;
            $response["success"] = 1;
            echo json_encode($response);

            $pdo = null;
          }
          else{
            // build response for no section id
            $response = array();
            $response["message"] = "ERROR INSERTING into is_in table: section_id ".$this->section_id." does not exist in class_section table";
            $response["success"] = 0;
            echo json_encode($response);
          }
        }
        else{
          // build response for no class id
          $response = array();
          $response["message"] = "ERROR INSERTING into is_in table: class_id ".$this->class_id." does not exist in class_course table";
          $response["success"] = 0;
          echo json_encode($response);
        }
      }
      else{
        // build response for no student id
        $response = array();
        $response["message"] = "ERROR INSERTING into is_in table: student_id ".$this->student_id." does not exist in student_account table";
        $response["success"] = 0;
        echo json_encode($response);
      }


    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT student_id, class_id, section_id
              FROM is_in
              WHERE student_id LIKE :student_id
                AND class_id LIKE :class_id
                AND section_id LIKE :section_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':student_id', $this->student_id);
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
      $response["message"] = "Success SELECTING from is_in";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
