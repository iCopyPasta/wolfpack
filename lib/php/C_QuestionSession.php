<?php
  /*
  This class is used to insert and select from table "question_session".
  ___________________
  |question_session |
  |_________________|
  |id               |
  |-----------------|
  |class_id         |
  |question_set_id  |
  |start_time       |
  |end_time         |
  |start_date       |
  |_________________|

  This table has references the class_id and question_set_id from the class_course_section and question_set tables.
  For this reason, checks are made against those tables to ensure the student_id and class_id given exist in their respective tables.

  Example Usage:

  // insert a new question_session
  $newObject = new QuestionSession('1', '1', '1', '2018-03-22 09:31:29', '2018-03-22 09:31:29', '2018-03-22 09:31:29');
  $newObject->insert();

  // select a question_session
  $newObject = new QuestionSession('1', '1', '1', '2018-03-22 09:31:29', '2018-03-22 09:31:29', '2018-03-22 09:31:29');
  $retVal = json_decode($newObject->select());

  // to test for no rows existing
  $newObject = new QuestionSession('1', '1', '1', '2018-03-22 09:31:29', '2018-03-22 09:31:29', '2018-03-22 09:31:29');
  $retVal = json_decode($newObject->select());
  if(array_key_exists(1, $retVal)){
    echo 'exists<br>';
  }
  else{
    echo 'no exists<br>';
  }

*/

  class QuestionSession{
    private $id;
    private $class_id;
    private $question_set_id;
    private $start_time;
    private $end_time;
    private $start_date;

    function __construct($id, $class_id, $question_set_id, $start_time, $end_time, $start_date){
      $this->__set('id', $id);
      $this->__set('class_id', $class_id);
      $this->__set('question_set_id', $question_set_id);
      $this->__set('start_time', $start_time);
      $this->__set('end_time', $end_time);
      $this->__set('start_date', $start_date);
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

      $sql = "INSERT INTO question_session
                              (class_id, question_set_id, start_time, end_time, start_date)
                              VALUES (:class_id, :question_set_id, :start_time, :end_time, :start_date)";
      $stmt = $pdo->prepare($sql);
      include_once('isIdExistFunctions.php');
      $isClassIdExist = isClassIdExist($this->__get('class_id'));
      $isQuestionSetIdExist = isQuestionSetIdExist($this->__get('question_set_id'));

      if($isQuestionSetIdExist){
        if($isClassIdExist){
          // classId and sectionId exist; attempt to insert
          try{
            $stmt->execute(['class_id' => $this->class_id, 'question_set_id' => $this->question_set_id,
                            'start_time' => $this->start_time, 'end_time' => $this->end_time,
                            'start_date' => $this->start_date]);
          }catch (Exception $e){
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR INSERTING: ".$this->class_id." ".
                                                      $this->question_set_id." ".
                                                      $this->start_time." ".
                                                      $this->end_time. " ".
                                                      $this->start_date." ".
                                                      $e->getMessage();
            $response["success"] = 0;
            return json_encode($response);
          }

          // success JSON response
          $response = array();
          $response["message"] = "Inserted: ".$this->class_id." ".
                                              $this->question_set_id." ".
                                              $this->start_time." ".
                                              $this->end_time. " ".
                                              $this->start_date;
          $response["success"] = 1;
          $response["idInserted"] = $pdo->lastInsertId();
          return json_encode($response);
        }else{
          // build response for no class id
          $response = array();
          $response["message"] = "ERROR INSERTING into question_session table: class_id ".$this->class_id." does not exist in class_course_section table";
          $response["success"] = 0;
          return json_encode($response);
        }
      }else{
        // build response for no question set id
        $response = array();
        $response["message"] = "ERROR INSERTING into question_session table: question_set_id ".$this->question_set_id." does not exist in question_set table";
        $response["success"] = 0;
        return json_encode($response);
      }
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT id, class_id, question_set_id, start_time, end_time, start_date
              FROM question_session
              WHERE id LIKE :id
                AND class_id LIKE :class_id
                AND question_set_id LIKE :question_set_id
                AND start_time LIKE :start_time
                AND end_time LIKE :end_time
                AND start_date LIKE :start_date";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $this->id);
      $stmt->bindValue(':class_id', $this->class_id);
      $stmt->bindValue(':question_set_id', $this->question_set_id);
      $stmt->bindValue(':start_time', $this->start_time);
      $stmt->bindValue(':end_time', $this->end_time);
      $stmt->bindValue(':start_date', $this->start_date);

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
      $response["message"] = "Success SELECTING from question_session";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }

  }
?>
