<?php
$currentPage = isset($_POST['inputCurrentPageNumber']) ? $_POST['inputCurrentPageNumber'] : null;
$email = isset($_POST['inputEmail']) ? $_POST['inputEmail'] : null;



$class1 = array("course_attribute" => "CMPSC", "class_course_number" => "122", "title" => "Intermediate Programming");
$class2 = array("course_attribute" => "CMPSC", "class_course_number" => "469", "title" => "Formal Languages");
$class3 = array("course_attribute" => "CMPSC", "class_course_number" => "430", "title" => "Database Design");
$class4 = array("course_attribute" => "CMPSC", "class_course_number" => "121", "title" => "Intro to C++");
$class5 = array("course_attribute" => "CMPSC", "class_course_number" => "470", "title" => "Compiler Construction");
$class6 = array("course_attribute" => "CMPSC", "class_course_number" => "472", "title" => "Operating Systems");
$class7 = array("course_attribute" => "CMPSC", "class_course_number" => "360", "title" => "Discrete Mathematics");
$class8 = array("course_attribute" => "CMPSC", "class_course_number" => "488", "title" => "Senior Design Project");
$class9 = array("course_attribute" => "CMPSC", "class_course_number" => "487", "title" => "Software Engineering");
$class10 = array("course_attribute" => "CMPSC", "class_course_number" => "444", "title" => "Secure Programming");
$class11 = array("course_attribute" => "CMPSC", "class_course_number" => "441", "title" => "Dank AI Memes");



switch($currentPage){
	case 1:

	$response = array($class1, $class2, $class3, $class4, $class5);

	echo json_encode($response);
	
	break;

	case 2:
	$response = array($class6, $class7, $class8, $class9, $class10);
	echo json_encode($response);
	break;

	case 3:
	$response = array($class11);
	echo json_encode($response);
	
	break;

	default:
	$response = array();
	$response["message"] = "no suitable page #'s switch statement found in PHP script pabz put";
	$response["success"] = 0;
	echo json_encode($response);
	break;

}
?>