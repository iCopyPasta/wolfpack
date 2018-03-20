<?php session_start();
unset($_SESSION["user"]); 
unset($_SESSION["accountType"]); 
header("Location: ../index.php"); ?>