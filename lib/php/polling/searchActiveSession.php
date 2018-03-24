<?php

    /*
    
    returns a question_set_id and question_session_id for a live polling session.

    expected POST variables:
    "inputClassId" - the given class_id you want to know for which there is an open poll
        
        
    return: JSON obj containing the question set id and question session id for a live polling session.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    {"results":[{"question_set_id":"1021","question_session_id":"107"}]}
    //array is empty if nothing is found
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputClassId = isset($_POST["inputClassId"]) ? (int) $_POST["inputClassId"] : null;        
        
        include_once('../Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT active_question_set.question_set_id, 
                       active_question_set.question_session_id
                FROM active_question_set
                WHERE active_question_set.class_id = :inputClassId";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputClassId', $inputClassId, PDO::PARAM_INT);

        
        try {
            $result->execute();
        }catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["results"] = array("question_set_id" => null, "question_session_id" => null);
            echo json_encode($response);
            die();
            exit();
        }

        $response = array();
        $retVal = array();
        
        while($list = $result->fetch(PDO::FETCH_ASSOC)){
            array_push($retVal, $list);
        }
        $response["results"] = $retVal;
        
        echo json_encode($response);
        die();
        exit();
              
    } else{
        echo "";
    }

?>
