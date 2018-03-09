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
    private $startTime;
    private $endTime;
    //private $results;  //TODO: needs more planning

    function __construct($loq, $cc,$cs,$st, $et) {
      $this->__set('listOfQuestions',$loq);
      $this->__set('classCourse', $cc);
      $this->__set('classSection',$cs);
      $this->__set('startTime',$st);
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

    public function isActive(){
      $currTime = time();

    }

  }
?>
