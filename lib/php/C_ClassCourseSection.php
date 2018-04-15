<?php
  /*
  This class is used by the teacher to insert or select table "class_section".

   _____________________
  |class_course_section |
  |_____________________|
  |class_id             |
  |---------------------|
  |title                |
  |description          |
  |offering             |
  |location             |
  |_____________________|

  Example Usage:

  // insert a new section
    $class = new ClassCourseSection('%', 'a title', 'a description', 'a offering', 'a location');
    $class->insert();

  // selecting a section; 0th element is success message; 1th to end are rows from table
    $class = new ClassCourseSection('276', '%', '%', '%', '%');
    $retVal = json_decode($class->select());
    $class_id = $retVal[1]->class_id;
    $title = $retVal[1]->title;
    $description = $retVal[1]->description;
    $offering = $retVal[1]->offering;
    $location = $retVal[1]->location;


  // delete a ClassCourseSection
    $class = new ClassCourseSection('%', 'testDeleteClass1', 'testDeleteClass1', 'testDeleteClass1', 'testDeleteClass1');
    echo $class->insert();

    $class = new ClassCourseSection('%', 'testDeleteClass1', 'testDeleteClass1', 'testDeleteClass1', 'testDeleteClass1');
    echo $class->delete();

  // update a ClassCourseSection
    $class = new ClassCourseSection('276', '%', '%', '%', '%');
    $retVal = json_decode($class->select());
    $class_id = $retVal[1]->class_id;
    $title = $retVal[1]->title;
    $description = $retVal[1]->description;
    $offering = $retVal[1]->offering;
    $location = $retVal[1]->location;

    $description = "i am only changing the description";

    $retVal = json_decode($class->update($title, $description, $offering, $location));

    var_dump($retVal);

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

    public function delete(){
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "DELETE
              FROM class_course_section
              WHERE class_id LIKE :class_id
                AND title LIKE :title
                AND description LIKE :description
                AND offering LIKE :offering
                AND location LIKE :location
                ";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':class_id', $this->__get('class_id'));
      $stmt->bindValue(':title', $this->__get('title'));
      $stmt->bindValue(':description', $this->__get('description'));
      $stmt->bindValue(':offering', $this->__get('offering'));
      $stmt->bindValue(':location', $this->__get('location'));

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR DELETING: ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      $pdo = null;
      $response = array();
      $response["message"] = "Success DELETING from class_course_section";
      $response["success"] = 1;
      $response["rowCount"] = $stmt->rowCount();
      return json_encode($response);
    }

    public function update($newTitle, $newDescription, $newOffering, $newLocation){
//      _____________________
//      |class_course_section |
//      |_____________________|
//      |class_id             |
//      |---------------------|
//      |title                |
//      |description          |
//      |offering             |
//      |location             |
//      |_____________________|
      $connection = new Connection;
      $pdo = $connection->getConnection();
      $sql = "UPDATE class_course_section
              SET title = :newTitle,
                  description = :newDescription,
                  offering = :newOffering,
                  location = :newLocation
              WHERE class_id LIKE :class_id
                AND title LIKE :title
                AND description LIKE :description
                AND offering LIKE :offering
                AND location LIKE :location
                ";

      $stmt = $pdo->prepare($sql);

      $stmt->bindValue(':newTitle', $newTitle);
      $stmt->bindValue(':newDescription', $newDescription);
      $stmt->bindValue(':newOffering', $newOffering);
      $stmt->bindValue(':newLocation', $newLocation);

      $stmt->bindValue(':class_id', $this->__get('class_id'));
      $stmt->bindValue(':title', $this->__get('title'));
      $stmt->bindValue(':description', $this->__get('description'));
      $stmt->bindValue(':offering', $this->__get('offering'));
      $stmt->bindValue(':location', $this->__get('location'));

      try{
        $stmt->execute();
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR UPDATING ".$e->getMessage();
        $response["success"] = 0;
        return json_encode($response);
      }

      // success JSON response
      $response = array();
      $response["message"] = "Update successful";
      $response["success"] = 1;
      $response["rowCount"] = $stmt->rowCount();
      return json_encode($response);

    }



  }
?>
