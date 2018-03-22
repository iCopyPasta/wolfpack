<?php

    /*
    
    returns a paginated list of all the classes a student belongs to

    expected POST variables:
        "currentPage" - the current page
        "rowsPerPage" - rows per page
        "student_id - students id number
        
    return: JSON obj containing a list of classes the student belongs to

    Example Usage:
    ensure you are using POST variables for the expected post variables

    Example Return JSON Value:
    {"totalPages":1,
    "totalResults":"6",
    "success":1,
    "results":[
    {"class_id":"13",
    "title":"Sed pariatur laboriosam perferendis sed sint.",
    "description":"Rerum voluptas in quisquam nisi natus. Accusamus ut perferendis reiciendis quasi neque provident voluptatem. Quia ut repudiandae assumenda qui.",
    "offering":"2014-09-07",
    "location":"804 Valentin Coves Suite 389\nSouth William, GA 25690"}
    ,
    {
    "class_id":"38",
    "title":"Aut mollitia dolore dolore voluptatem nam culpa error.",
    "description":"Veritatis eos eius ut ad et. Ipsam nostrum consequatur libero sit minima sunt. Illo ullam velit laudantium quia iusto. Praesentium voluptatem dolorem fugiat est omnis ullam aut nostrum.",
    "offering":"2004-10-18",
    "location":"9526 Harris Prairie\nMarcellusside, SD 19327-7976"
    }
    ]}
    

    */

    //default values for important variables for pagination
    $numrows = 0;
    $rowsPerPage = 10;
    $totalPages = 1;
    $currentPage = 1;
    $student_id = 1;
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $rowsPerPage = isset($_POST["rowsPerPage"]) ? (int) $_POST["rowsPerPage"] : $rowsPerPage;
        $student_id = isset($_POST["student_id"]) ? (int) $_POST["student_id"] : $student_id;
        
        // get the current page or set a default
        $currentPage = isset($_POST["currentPage"])? (int) $_POST["currentPage"] : $currentPage;
        
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT COUNT(*)
                FROM student_is_in, class_course_section
                WHERE :student_id = student_is_in.student_id
                AND student_is_in.class_id = class_course_section.class_id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':student_id', $student_id);
        
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

        $sql = "SELECT  class_course_section.class_id, 
                        class_course_section.title, 
                        class_course_section.description,
                        class_course_section.offering,
                        class_course_section.location
                        
                FROM student_is_in, class_course_section
                WHERE :student_id = student_is_in.student_id
                AND student_is_in.class_id = class_course_section.class_id";
        
        $result = $pdo->prepare($sql);
        $result->bindValue(':student_id', $student_id);
        
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