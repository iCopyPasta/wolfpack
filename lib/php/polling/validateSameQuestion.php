<?php

    /*
    
    returns a question_id and question_history_id for a live question

    expected POST variables:
    "inputQuestionSetId" - the given question_set_id you want to know for which there is an open poll
    "inputQuestionId" - is the same question id being used? (new question used in same poll)
    "inputQuetsionHistoryId" - is the same history id being used? (i.e. on-off-on)
    "inputQuestionSessionId" - is the same session being used? (new session, same question)
        
        
    return: a JSON object that should match exactly to a particular instance/usage of a question at a point in time

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    {
        "results": [{
            "question_id": "2015",
            "question_set_id": "1021",
            "question_history_id": "88",
            "question_session_id": "164"
        }]
    }

    //results is empty if nothing is found
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputQuestionSetId = isset($_POST["inputQuestionSetId"]) ? $_POST["inputQuestionSetId"] : null;   
        
        $inputQuestionId = isset($_POST["inputQuestionId"]) ? $_POST["inputQuestionId"] : null;
        
        $inputQuetsionHistoryId = isset($_POST["inputQuetsionHistoryId"]) ? 
            $_POST["inputQuetsionHistoryId"] : null;
        
        $inputQuestionSessionId = isset($_POST["inputQuestionSessionId"]) ? 
            $_POST["inputQuestionSessionId"] : null;
        
        include_once('../Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT active_question.question_id,
                       active_question.question_set_id,
                       active_question.question_history_id,
                       active_question_set.question_session_id
                       
                FROM active_question, active_question_set
                WHERE active_question.question_set_id = :inputQuestionSetId
                AND active_question.question_id = :inputQuestionId
                AND active_question.question_history_id = :inputQuestionHistoryId
                AND active_question_set.question_session_id = :inputQuestionSessionId";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputQuestionSetId', $inputQuestionSetId, PDO::PARAM_INT);
        $result->bindValue(':inputQuestionId', $inputQuestionId, PDO::PARAM_INT);
        $result->bindValue(':inputQuestionHistoryId', $inputQuetsionHistoryId, PDO::PARAM_INT);
        $result->bindValue(':inputQuestionSessionId', $inputQuestionSessionId, PDO::PARAM_INT);

        
        try {
            $result->execute();
        }catch (Exception $e) {
            // fail JSON response
            $response = array();
            echo $e->getMessage()."\n";
            $response["results"] = array();
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
