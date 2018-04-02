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
    {
    "totalPages": 2,
    "totalResults": "10",
    "success": 1,
    "results": [{
        "class_id": "162",
        "title": "Molestiae in voluptatum dolorem deserunt unde.",
        "description": "Cumque possimus numquam corrupti excepturi. Sed alias quod cupiditate velit ullam. Molestias repellat aut quis qui. Rerum esse alias dignissimos ut.",
        "offering": "2012-04-03",
        "location": "62653 Vladimir Village Apt. 112\nEast Leann, MS 93552",
        "first_name": "Brianne",
        "last_name": "Hermann"
    }, {
        "class_id": "120",
        "title": "Velit mollitia quia rerum ut.",
        "description": "Ipsum ut est et vitae. Voluptas aliquid nobis et aliquam. Quia labore dicta praesentium.",
        "offering": "1981-08-29",
        "location": "78318 Deangelo Valley Suite 367\nOrrintown, OH 58204-4589",
        "first_name": "Shane",
        "last_name": "Kuhic"
    }, {
        "class_id": "13",
        "title": "Sed pariatur laboriosam perferendis sed sint.",
        "description": "Rerum voluptas in quisquam nisi natus. Accusamus ut perferendis reiciendis quasi neque provident voluptatem. Quia ut repudiandae assumenda qui.",
        "offering": "2014-09-07",
        "location": "804 Valentin Coves Suite 389\nSouth William, GA 25690",
        "first_name": "Alize",
        "last_name": "Nolan"
    }, {
        "class_id": "162",
        "title": "Molestiae in voluptatum dolorem deserunt unde.",
        "description": "Cumque possimus numquam corrupti excepturi. Sed alias quod cupiditate velit ullam. Molestias repellat aut quis qui. Rerum esse alias dignissimos ut.",
        "offering": "2012-04-03",
        "location": "62653 Vladimir Village Apt. 112\nEast Leann, MS 93552",
        "first_name": "Carolanne",
        "last_name": "Pacocha"
    }, {
        "class_id": "38",
        "title": "Aut mollitia dolore dolore voluptatem nam culpa error.",
        "description": "Veritatis eos eius ut ad et. Ipsam nostrum consequatur libero sit minima sunt. Illo ullam velit laudantium quia iusto. Praesentium voluptatem dolorem fugiat est omnis ullam aut nostrum.",
        "offering": "2004-10-18",
        "location": "9526 Harris Prairie\nMarcellusside, SD 19327-7976",
        "first_name": "Mara",
        "last_name": "West"
    }]
}
    

    */

    //default values for important variables for pagination
    $numrows = 0;
    $rowsPerPage = 5;
    $totalPages = 1;
    $currentPage = 1;
    $student_id = 1;
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        
        $student_id = isset($_POST["inputStudentId"]) ? $_POST["inputStudentId"] : $student_id;

        
        // get the current page or set a default
        $currentPage = isset($_POST["currentPage"])?  $_POST["currentPage"] : $currentPage;
        $rowsPerPage = isset($_POST["rowsPerPage"]) ? $_POST["rowsPerPage"] : $rowsPerPage;
        
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT COUNT(*)
                FROM student_is_in, class_course_section, teaches, teacher_account
                WHERE student_is_in.student_id = :studentId
                AND student_is_in.class_id = class_course_section.class_id
                AND class_course_section.class_id = teaches.class_id
                AND teaches.teacher_id = teacher_account.teacher_id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':studentId', $student_id, PDO::PARAM_INT);
        
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
                        class_course_section.location,
                        teacher_account.first_name,
                        teacher_account.last_name
                        
                FROM student_is_in, class_course_section, teaches, teacher_account
                WHERE student_is_in.student_id = :studentId
                AND student_is_in.class_id = class_course_section.class_id
                AND class_course_section.class_id = teaches.class_id
                AND teaches.teacher_id = teacher_account.teacher_id
                LIMIT $offset, $rowsPerPage";
        
        $result = $pdo->prepare($sql);
        $result->bindValue(':studentId', $student_id, PDO::PARAM_INT);
        
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