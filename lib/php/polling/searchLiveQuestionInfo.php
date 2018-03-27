<?php

    /*
    
    returns a question_id and question_history_id for a live question

    expected POST variables:
    "inputQuestionId" - the given question_id about which you want to know information 
        
    return: JSON obj containing the question set id and question session id for a live polling session.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    {
    "results": [{
        "question_id": "2015",
        "teacher_id": "1",
        "question_type": "Multiple Choice",
        "description": "This is a True/False Question",
        "potential_answers": "[\"This is the right answer\",\"This is the wrong answer\"]",
        "correct_answers": "[\"1\"]"
    }]
}
    
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputQuestionSetId = isset($_POST["inputQuestionId"]) ? (int) $_POST["inputQuestionId"] : null;        
        include_once('../Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT *
                FROM question
                WHERE question.question_id = :inputQuestionId";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputQuestionId', $inputQuestionSetId, PDO::PARAM_INT);

        
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
