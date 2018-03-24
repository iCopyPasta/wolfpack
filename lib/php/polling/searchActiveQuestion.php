<?php

    /*
    
    returns a question_id and question_history_id for a live question

    expected POST variables:
    "inputQuestionSetId" - the given question_id you want to know for which there is an open poll
        
        
    return: JSON obj containing the question set id and question session id for a live polling session.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputQuestionSetId = isset($_POST["inputQuestionSetId"]) ? (int) $_POST["inputQuestionSetId"] : null;        
        include_once('../Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT active_question.question_id, active_question.question_history_id
                FROM active_question
                WHERE active_question.question_set_id = :inputQuestionSetId";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputQuestionSetId', $inputQuestionSetId, PDO::PARAM_INT);

        
        try {
            $result->execute();
        }catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["results"] = array("question_id" => null, "question_history_id" => null);
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
