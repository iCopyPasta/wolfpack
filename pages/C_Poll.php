<?php
  /*
  This class is used by the teacher to issue or query a poll.

  Example Usage:

  //include the script
    include('/pages/C_Poll.php');

  // create a poll
    // Month: 2 digit String of format: xx
    // Day  : 2 digit String of format: xx
    // Year : 4 digit String of format: xxxx
    // Time : 6 digit String of format: xx:xx:xx
    $poll = new Poll('','','',
            '2018', '03', '09', '15:40:00',
            '2018', '04', '10', '10:00:00');

  // is the poll active? in other words, is the current time within the poll window?
    $retVal = $poll->isActive();
    if($retVal == true){
      echo 'true<br>';
    }
    else{
      echo 'false<br>';
    }

  // add a single question to the poll
    $selectQuestion = new Question('3', '%', '%', '%');
    $result = json_decode($selectQuestion->select(), true);
    $result = json_encode($result, true);
    $poll->addQuestion($result);

  // get an array containing a list of all the questions in the poll
    $poll->__get('listOfQuestions')

  // set the poll's class course
    $aCourse = new ClassCourse('%', '460', 'Olmstead 218', 'offering?');
    $poll->__set('classCourse', $aCourse->select());

  // get the poll's class course
    $theCourse = $poll->__get('classCourse');



  */

  class Poll{
    private $listOfQuestions;
    private $classCourse;
    private $classSection;

    private $startMonth;
    private $startDay;
    private $startYear;
    private $startTime;

    private $endMonth;
    private $endDay;
    private $endYear;
    private $endTime;
    //private $results;  //TODO: $results needs more planning

    //TODO: ensure that end is not before start

    //TODO: use "instance"/factory method approach, take multiple arguements... , default 5min duration if no end time given
    //    public function __construct() {
    //      $parameters = func_get_args();
    //      ...
    //    }

    // Month: 2 digit String of format: xx
    // Day  : 2 digit String of format: xx
    // Year : 4 digit String of format: xxxx
    // Time : 6 digit String of format: xx:xx:xx
    function __construct($cc,$cs, $sy, $sm, $sd, $st,$ey, $em, $ed, $et) {
      $this->listOfQuestions = array();
      $this->__set('classCourse', $cc);
      $this->__set('classSection',$cs);

      $this->__set('startYear',$sy);
      $this->__set('startMonth',$sm);
      $this->__set('startDay',$sd);
      $this->__set('startTime',$st);

      $this->__set('endYear',$ey);
      $this->__set('endMonth',$em);
      $this->__set('endDay',$ed);
      $this->__set('endTime',$et);
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

    // $question : a JSON encoded Question object
    // return: JSON encoded object with message(string) and success(boolean) values
    public function addQuestion($question){
      $response = array();

      try {
        $questionJSON = json_decode($question);
      }catch(Exception $e){
        $response["message"] = "Error: addQuestion to poll object. ".$e;
        $response["success"] = 0;
        return $response;
      }

      try{
        array_push($this->listOfQuestions, $questionJSON);
      }catch(Exception $e){
        $response["message"] = "Error: addQuestion to poll object. ".$e;
        $response["success"] = 0;
        return $response;
      }

      $response["message"] = "Success: addQuestion to poll object";
      $response["success"] = 1;
      return json_encode($response);
    }

    //note that day and month must be two digits (leading zero) or logic will fail
    public function isActive(){
      //get start date and time
      $start = $this->__get('startYear').'-'.
              $this->__get('startMonth').'-'.
              $this->__get('startDay').' '.
              $this->__get('startTime');

      //get end date and time
      $end = $this->__get('endYear').'-'.
              $this->__get('endMonth').'-'.
              $this->__get('endDay').' '.
              $this->__get('endTime');

      //get current date and time
      date_default_timezone_set('America/New_York'); // CDT
      $currDate = date("Y-m-d H:i:s");

      //poll has started: current time is after start time
      if($currDate > $start){
        //poll hasn't ended yet: current time is before end time
        if($currDate < $end){
          return true;
        }
        //poll has ended: current time is after end time
        else{
          return false;
        }
      }
      //poll hasn't started yet: current time is before start time
      else{
        return false;
      }

      //TODO: error cases
      return null;
    }

    //TODO:

  }
?>
