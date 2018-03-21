<?php
//Creates a class using POST variables
   session_start();

    include_once('Connection.php');
    include_once('createClass_teacher.php');
    $connection = new Connection;

    $title = $_POST["title"];
    $description = $_POST["description"];
    $offering = $_POST["offering"];
    $location = $_POST["location"];
   
    createClass($_SESSION['id'],"%","%",$title,$description,$offering,$location);

    header("Location: ..\..\index.php");

?>