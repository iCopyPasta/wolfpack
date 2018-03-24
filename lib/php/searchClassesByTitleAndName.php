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
    {
    "totalPages": 30,
    "totalResults": "300",
    "success": 1,
    "results": [{
        "class_id": "3",
        "title": "Repellat velit est ut saepe.",
        "description": "Velit quis sint laudantium officiis velit voluptatum. Soluta assumenda consequuntur ut unde voluptas molestias sed repellendus. Molestias aut ut voluptas.",
        "offering": "1983-02-16",
        "location": "65336 Turner Springs\nPourosborough, FL 49915"
    }, {
        "class_id": "3",
        "title": "Repellat velit est ut saepe.",
        "description": "Velit quis sint laudantium officiis velit voluptatum. Soluta assumenda consequuntur ut unde voluptas molestias sed repellendus. Molestias aut ut voluptas.",
        "offering": "1983-02-16",
        "location": "65336 Turner Springs\nPourosborough, FL 49915"
    }, {
        "class_id": "5",
        "title": "Ex modi et aut.",
        "description": "Sed perferendis ad velit consequuntur dignissimos impedit vero ut. Autem ab nulla eum. Asperiores vel dolor ut blanditiis officia magnam. Soluta nobis soluta accusamus doloribus repudiandae.",
        "offering": "1971-05-20",
        "location": "5343 Ezequiel Square\nSouth Oswaldomouth, VT 64119-9317"
    }]}
    */

    //default values for important variables for pagination
    $numrows = 0;
    $rowsPerPage = 5;
    $totalPages = 1;
    $currentPage = 1;
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        #echo "inside reques_method == post\n";
        
        $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : "%";
        $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : "%";
        $title = isset($_POST["title"]) ? $_POST["title"] : "%";
        
        
        // get the current page or set a default
        $currentPage = isset($_POST["currentPage"])? (int) $_POST["currentPage"] : $currentPage;
        $rowsPerPage = isset($_POST["rowsPerPage"]) ? (int) $_POST["rowsPerPage"] : $rowsPerPage;
        
        //debugging statements:
        /*echo "--------- initial data fetch ------------\n";
        echo "first name: "; echo $firstName; echo "\n";
        echo "last name: "; echo $lastName;echo "\n";
        echo "title: ";echo $title; echo "\n";
        echo "current page: ";echo $currentPage; echo "\n";
        echo "rowsPerPage: ";echo $rowsPerPage; echo "\n";*/
        
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT COUNT(*)
                FROM class_course_section, teaches, teacher_account
                WHERE teacher_account.first_name LIKE :firstName
                AND teacher_account.last_name LIKE :lastName
                AND class_course_section.title LIKE :title
                AND class_course_section.class_id = teaches.class_id
                AND teaches.teacher_id = teacher_account.teacher_id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $result->bindValue(':lastName', $lastName,PDO::PARAM_STR);
        $result->bindValue(':title', $title,PDO::PARAM_STR);
        
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
        
        /*echo "--------- after first round of SQL values ------------";
        echo "first name: "; echo $firstName; echo "\n";
        echo "last name: "; echo $lastName;echo "\n";
        echo "title: ";echo $title; echo "\n";
        echo "current page: ";echo $currentPage; echo "\n";
        echo "rowsPerPage: ";echo $rowsPerPage; echo "\n";
        echo "total pages: ";echo $totalPages; echo "\n";
        echo "numrows: ";echo $numrows; echo "\n";*/

        $sql = "SELECT class_course_section.class_id, 
                        class_course_section.title,
                        class_course_section.description, 
                        class_course_section.offering, 
                        class_course_section.location
                        
                FROM class_course_section, teaches, teacher_account
                WHERE teacher_account.first_name LIKE :firstName
                AND teacher_account.last_name LIKE :lastName
                AND class_course_section.title LIKE :title
                AND class_course_section.class_id = teaches.class_id
                AND teaches.teacher_id = teacher_account.teacher_id
                LIMIT $offset, $rowsPerPage";
        
        $result = $pdo->prepare($sql);
        $result->bindValue(':firstName', $firstName);
        $result->bindValue(':lastName', $lastName);
        $result->bindValue(':title', $title);
        
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
              
    } else{
        echo "<br/>this didn't work";
    }

?>
