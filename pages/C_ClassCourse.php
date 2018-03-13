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
    private $class_course_number;
    private $location;
    private $offering;
    private $title;

    function __construct($id, $c,$l,$o, $t) {
      $this->__set('class_id',$id);
      $this->__set('class_course_number', $c);
      $this->__set('location',$l);
      $this->__set('offering',$o);
      $this->__set('title',$t);
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
                              (class_course_number, location, offering, title)
                              VALUES (:class_course_number, :location, :offering, :title)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['class_course_number' => $this->class_course_number,
                        'location' => $this->location,
                        'offering' => $this->offering,
                        'title' => $this->title]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->class_course_number." ".$this->location." ".$this->offering." ".$this->title." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] = "Inserted: ".$this->class_course_number." ".$this->location." ".$this->offering." ".$this->title;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT class_id, class_course_number, location, offering
              FROM class_course
              WHERE class_id LIKE :class_id
                AND class_course_number LIKE :class_course_number
                AND location LIKE :location
                AND offering LIKE :offering
                AND title LIKE :title";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':class_id', $this->class_id);
      $stmt->bindValue(':class_course_number', $this->class_course_number);
      $stmt->bindValue(':location', $this->location);
      $stmt->bindValue(':offering', $this->offering);
      $stmt->bindValue(':title', $this->title);

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
      $response["message"] = "Success SELECTING from ClassCourse";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);


    }

  }
?>
