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

  class Answers{
    private $student_id;
    private $session_id;
    private $question_history_id;
    private $answer_type;
    private $answer;
    private $submit_time;

    function __construct($student_id, $session_id, $question_history_id, $answer_type, $answer, $submit_time) {
      $this->__set('student_id',$student_id);
      $this->__set('session_id',$session_id);
      $this->__set('question_history_id',$question_history_id);
      $this->__set('answer_type',$answer_type);
      $this->__set('answer',$answer);
      $this->__set('submit_time',$submit_time);
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

      $sql = "INSERT INTO answers
                (student_id, session_id, question_history_id, answer_type, answer, submit_time)
                VALUES (:student_id, :session_id, :question_history_id, :answer_type, :answer, :submit_time)";
      $stmt = $pdo->prepare($sql);

      include_once('isIdExistFunctions.php');
      $isStudentIdExist = isStudentIdExist($this->__get('student_id'));
      $isQuestionSessionIdExist = isQuestionSessionIdExist($this->__get('session_id'));
      $isQuestionHistoryIdExist = isQuestionHistoryIdExist($this->__get('question_history_id'));

      if($isStudentIdExist){
        if($isQuestionSessionIdExist) {
          if ($isQuestionHistoryIdExist) {
            // classId sectionId, and question history id exist; attempt to insert
            try {
              $stmt->execute(['student_id' => $this->student_id,
                              'session_id' => $this->session_id,
                              'question_history_id' => $this->question_history_id,
                              'answer_type' => $this->answer_type,
                              'answer' => $this->answer,
                              'submit_time' => $this->submit_time]);
            } catch (Exception $e) {
              // fail JSON response
              $response = array();
              $response["message"] = "ERROR INSERTING: " . $this->student_id . " " . $this->session_id . " " . $this->question_history_id . " " . $e->getMessage();
              $response["success"] = 0;
              return json_encode($response);
            }

            // success JSON response
            $response = array();
            $response["message"] = "Inserted: " . $this->student_id . " " . $this->session_id . " " . $this->question_history_id ;
            $response["success"] = 1;
            return json_encode($response);
          }else {
            // question history id does not exist
            $response = array();
            $response["message"] = "ERROR INSERTING into answers table: question_history_id " . $this->question_history_id . " does not exist in class_course table";
            $response["success"] = 0;
            return json_encode($response);
          }
        }else{
          // session id does not exist
          $response = array();
          $response["message"] = "ERROR INSERTING into answers table: session_id " . $this->session_id . " does not exist in class_course table";
          $response["success"] = 0;
          return json_encode($response);
        }
      }else{
        // student id does not exist
        $response = array();
        $response["message"] = "ERROR INSERTING into answers table: student_id ".$this->student_id." does not exist in student_account table";
        $response["success"] = 0;
        return json_encode($response);
      }
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT student_id, session_id, question_history_id, answer_type, answer, submit_time
              FROM answers
              WHERE student_id LIKE :student_id
                AND session_id LIKE :session_id
                AND question_history_id LIKE :question_history_id
                AND answer_type LIKE :answer_type
                AND answer LIKE :answer
                AND submit_time LIKE :submit_time";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':student_id', $this->student_id);
      $stmt->bindValue(':session_id', $this->session_id);
      $stmt->bindValue(':question_history_id', $this->question_history_id);
      $stmt->bindValue(':answer_type', $this->answer_type);
      $stmt->bindValue(':answer', $this->answer);
      $stmt->bindValue(':submit_time', $this->submit_time);

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
      $response["message"] = "Success SELECTING from answers";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }
  }
?>
