<?php
  /*
  This class is used by the teacher to issue or query a poll.

  Example Usage:

  //TODO: example usage

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
    //private $results;  //TODO: needs more planning

    function __construct($loq, $cc,$cs, $sy, $sm, $sd, $st,$ey, $em, $ed, $et) {
      $this->__set('listOfQuestions',$loq);
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

    public function addQuestion($question){

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

      //TODO: is current date/time after start date/time and before end date/time?
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



  }
?>
