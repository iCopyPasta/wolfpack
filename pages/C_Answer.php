<?php
  /*
  TODO: update C_Answer usage section
  This class is used to insert a new answer into table "answers".

  Example Usage:

  $aAnswer = new Answer('ignored', '1', '<timeValue>');
  $aAnswer->insert();

  $aAnswer = new Question('1', '%', '%', '%');
  $result = json_decode($selectQuestion->select(), true);

  */

  class Answer{
    private $question_id;
    private $attendee_account_id;
    private $submit_time;
    private $answer;

    function __construct($questionId, $attendee_account_id, $submit_time, $answer) {
      $this->__set('question_id', $questionId);
      $this->__set('attendee_account_id', $attendee_account_id);
      $this->__set('submit_time', $submit_time);
      $this->__set('answer', $answer);
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

      $sql = "INSERT INTO answers
                              (attendee_account_id, submit_time, answer)
                              VALUES (:attendee_account_id, :submit_time, :answer)";
      $stmt = $pdo->prepare($sql);

      try{
        $stmt->execute(['attendee_account_id' => $this->attendee_account_id, 'submit_time' => $this->submit_time, 'answer' => $this->answer]);
      }catch (Exception $e){
        // fail JSON response
        $response = array();
        $response["message"] = "ERROR INSERTING: ".$this->attendee_account_id." ".$this->submit_time." ".$this->answer." ".$e->getMessage();
        $response["success"] = 0;
        echo json_encode($response);
        die();
      }

      // success JSON response
      $response = array();
      $response["message"] =  "Inserted: ".$this->attendee_account_id." ".$this->tags." ".$this->submit_time." ".$this->answer;
      $response["success"] = 1;
      echo json_encode($response);

      $pdo = null;
    }

    public function select(){
      // may be better to make connection in calling page
//      include('Connection.php');
      $connection = new Connection;
      $pdo = $connection->getConnection();

      $sql = "SELECT question_id, attendee_account_id, submit_time, answer
              FROM answers
              WHERE question_id LIKE :question_id
                AND attendee_account_id LIKE :attendee_account_id
                AND submit_time LIKE :submit_time
                AND answer LIKE :answer";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':question_id', $this->question_id);
      $stmt->bindValue(':attendee_account_id', $this->attendee_account_id);
      $stmt->bindValue(':submit_time', $this->submit_time);
      $stmt->bindValue(':answer', $this->answer);

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
      $response["message"] = "Success SELECTING from Answers";
      $response["success"] = 1;
      $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
      array_unshift($retVal, $response);
      return json_encode($retVal);
    }

  }
?>
