<?php

    /*
    
    returns a paginated list of all the classes for a search based on the teacher's name and class title

    expected POST variables:
        "currentPage" - the current page
        "rowsPerPage" - rows per page
        "title" - the title of class_course_section
        "firstName" - the first name of the teacher
        "lastName" - the last name of the teacher
        
    return: JSON obj containing a list of classes the student belongs to

    Example Usage:
    ensure you are using POST variables for the expected post variables

    Example Return JSON Value:
    
    */

    //default values for important variables for pagination
    $numrows = 0;
    $rowsPerPage = 10;
    $totalPages = 1;
    $currentPage = 1;


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        
        $student_id = isset($_POST["student_id"]) ? (int) $_POST["student_id"] : $student_id;
        $class_id = isset($_POST["class_id"]) ? (int) $_POST["class_id"] : $class_id;
        
        // get the current page or set a default
        $currentPage = isset($_POST["currentPage"])? (int) $_POST["currentPage"] : $currentPage;
        $rowsPerPage = isset($_POST["rowsPerPage"]) ? (int) $_POST["rowsPerPage"] : $rowsPerPage;
        
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT COUNT(*)
                FROM student_account, student_is_in, class_course_section, question_session, question_set
                WHERE student_account.student_id LIKE :student_id
                AND student_account.student_id = student_is_in.student_id
                AND student_is_in.class_id = class_course_section.class_id
                AND class_course_section.class_id = :class_id
                AND question_session.class_id = class_course_section.class_id
                AND question_session.question_set_id = question_set.question_set_id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':student_id', $student_id);
        $result->bindValue(':class_id', $class_id);
        
        
        try {
            $result->execute();
        }catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR SELECTING: " . $e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
            exit();
        }
        
        // number of rows for the given executed query
        $r = $result->fetch(PDO::FETCH_NUM);
        $numrows = $r[0];
        
        // find out total pages
        $totalPages = ceil($numrows / $rowsPerPage);
        
        // if current page is greater than total pages...
        if ($currentPage > $totalPages) {
            // set current page to last page
            $currentPage = $totalPages;
        } 
        // if current page is less than first page...
        if ($currentPage < 1) {
        
            $currentPage = 1;
        } 
        
        // the offset of the list, based on current page
        $offset = ($currentPage - 1) * $rowsPerPage;
        
        // get the info from the db

        $sql = "SELECT question_session.id, question_set.question_set_id
                FROM student_account, student_is_in, class_course_section, question_session, question_set
                WHERE student_account.student_id LIKE :student_id
                AND student_account.student_id = student_is_in.student_id
                AND student_is_in.class_id = class_course_section.class_id
                AND class_course_section.class_id = :class_id
                AND question_session.class_id = class_course_section.class_id
                AND question_session.question_set_id = question_set.question_set_id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':student_id', $student_id);
        $result->bindValue(':class_id', $class_id);

        
        try {
            $result->execute();
        } catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["message"] = "ERROR SELECTING: " . $e->getMessage();
            $response["success"] = 0;
            echo json_encode($response);
            die();
            exit();
        }
        
        $response = array();
        $response["totalPages"] = $totalPages;
        $response["totalResults"] = $numrows;
        $response["success"] = 1;
        $retVal = array();
        
        while($list = $result->fetch(PDO::FETCH_ASSOC)){
            array_push($retVal, $list);
        }
        
        $response["results"] = $retVal;
        
        echo json_encode($response);
              
    }

?>