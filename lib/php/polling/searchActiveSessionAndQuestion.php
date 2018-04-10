<?php

    /*
    
    returns a question_id and question_history_id for a live question

    expected POST variables:
    "inputQuestionSetId" - the given question_id you want to know for which there is an open poll
    "inputClassId" - the given class_id you want to know for which there is an open poll   
        
    return: JSON obj containing the question set id and question session id for a live polling session.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    {"activeQuestionResults": [{"question_set_id":"1021","question_session_id":"107"}],
    "activeSessionResults":[{"question_id":"2015","question_history_id":"13"}]}
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputClassId = isset($_POST["inputClassId"]) ? $_POST["inputClassId"] : null;  
        $inputQuestionSetId = isset($_POST["inputQuestionSetId"]) ? $_POST["inputQuestionSetId"] : null;
        
        include_once('../Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "SELECT active_question_set.question_set_id, 
                       active_question_set.question_session_id,
                       question_set.question_set_name
                FROM active_question_set, question_set
                WHERE active_question_set.class_id = :inputClassId
                      AND question_set.question_set_id = active_question_set.question_set_id";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputClassId', $inputClassId, PDO::PARAM_INT);

        
        try {
            $result->execute();
        }catch (Exception $e) {
            // fail JSON response
            echo $e->getMessage();
            $response = array();
            $response["activeSessionResults"] = array();
            $response["activeQuestionResults"] = array();
            echo json_encode($response);
            die();
            exit();
        }

        $response = array();
        $retVal = array();
        $temp = 0;
        
        while($list = $result->fetch(PDO::FETCH_ASSOC)){
            $temp = $temp + 1;
            array_push($retVal, $list);
        }
        
        $response["activeSessionResults"] = $retVal;
        
        if($temp <= 0){
            $response = array();
            $response["activeSessionResults"] = array();
            $response["activeQuestionResults"] = array();
            echo json_encode($response);
            die();
            exit();
        } else{

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
                $response["activeSessionResults"] = array();
                $response["activeQuestionResults"] = array();
                echo json_encode($response);
                die();
                exit();
            }

            $retVal = array();

            while($miniList = $result->fetch(PDO::FETCH_ASSOC)){
                array_push($retVal, $miniList);
            }
            $response["activeQuestionResults"] = $retVal;

            echo json_encode($response);
            die();
            exit();
        }
              
    }
?>
