<?php
  /*
  This class is used to insert and select from table "is_in".
  _______________
  |is_in         |
  |______________|
  |student_id    |
  |class_id      |
  |--------------|
  |______________|

  This table has a composite primary key built from the student_account and class_course table.
  For this reason, checks are made against those tables to ensure the student_id and class_id given exist in their respective tables.

  Example Usage:

  // insert a new "is_in"
    $aIsIn = new IsIn(1, 15);
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

  class StudentIsIn{
    private $student_id;
    private $class_id;

    function __construct($stud_id, $classid) {
      $this->__set('student_id',$stud_id);
      $this->__set('class_id',$classid);
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

      $sql = "INSERT INTO student_is_in
                              (student_id, class_id)
                              VALUES (:student_id, :class_id)";
      $stmt = $pdo->prepare($sql);
      include_once('isIdExistFunctions.php');
      $isStudentIdExist = isStudentIdExist($this->__get('student_id'));
      $isClassIdExist = isClassIdExist($this->__get('class_id'));

      if($isStudentIdExist){
        if($isClassIdExist){
          // classId and sectionId exist; attempt to insert
          try{
            $stmt->execute(['student_id' => $this->student_id, 'class_id' => $this->class_id]);
          }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->student_id." ".$this->class_id." ".$e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
          }

          // success JSON response
          $response = array();
          $response["message"] = "Inserted: ".$this->student_id." ".$this->class_id;
          $response["success"] = 1;
          echo json_encode($response);

          $pdo = null;
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

      $sql = "SELECT student_id, class_id
              FROM student_is_in
              WHERE student_id LIKE :student_id
                AND class_id LIKE :class_id";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':student_id', $this->student_id);
      $stmt->bindValue(':class_id', $this->class_id);

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
      $response["message"] = "Success SELECTING from student_is_in";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }

    public function isStudentIdExist($aStudentId){
      include_once('/pages/C_StudentAccount.php');
      $student = new StudentAccount($aStudentId, '%', '%', '%', '%', '%', '%', '%', '%');
      $qJSON = json_decode($student->select(), true);
      // if a row was returned then the class_id exists
      return array_key_exists(1, $qJSON);
    }

    public function isClassIdExist($aClassId){
      include_once('/pages/C_ClassCourseSection.php');
      $class = new ClassCourseSection($aClassId, '%', '%', '%', '%');
      $qJSON = json_decode($class->select(), true);
      // if a row was returned then the class_id exists
      return array_key_exists(1, $qJSON);
    }

  }
?>
