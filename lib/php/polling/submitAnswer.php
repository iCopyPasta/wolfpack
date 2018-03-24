<?php

    /*
    
    submits an answer for the first, or nth time given a live question for a live session

    expected POST variables:
    "inputStudentId"
    "inputSessionId"    
    "inputQuestionHistoryId"
    "inputAnswerType"
    "inputAnswer"
    
    return: JSON obj with the status of the 'insert'.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputStudentId = isset($_POST["inputStudentId"]) ? (int) $_POST["inputStudentId"] : null;
        $inputSessionId = isset($_POST["inputSessionId"]) ? (int) $_POST["inputSessionId"] : null;
        
        $inputQuestionHistoryId = isset($_POST["inputQuestionHistoryId"]) ? (int) $_POST["inputQuestionHistoryId"] : null;
        
        $inputAnswerType = isset($_POST["inputAnswerType"]) ? (int) $_POST["inputAnswerType"] : null;
        $inputAnswer = isset($_POST["inputAnswer"]) ? (int) $_POST["inputAnswer"] : null;
        
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        // find out how many rows are in the table
        $sql = "IF EXISTS (
                        SELECT * 
                        FROM answers 
                        WHERE student_id = :inputStudentId,
                        session_id = :inputSessionId,
                        question_history_id = :inputQuestionHistoryId,
                        answer_type = :inputAnswerType,
                        )
                    UPDATE answers 
                    SET answer = :inputAnswer, 
                    WHERE student_id = :inputStudentId,
                          session_id = :inputSessionId,
                          question_history_id = :inputQuestionHistoryId,
                          answer_type = :inputAnswerType
                    ELSE
                    INSERT INTO answers (student_id, session_id, question_history_id, answer_type, answer)
                    VALUES(:inputStudentId, :inputSessionId, :inputQuestionHistoryId, :inputAnswerType,  :inputAnswer)";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputStudentId', $inputStudentId, PDO::PARAM_INT);
        $result->bindValue(':inputSessionId', $inputSessionId, PDO::PARAM_INT);
        $result->bindValue(':inputQuestionHistoryId', $inputQuestionHistoryId, PDO::PARAM_INT);
        $result->bindValue(':inputAnswerType', $inputAnswerType, PDO::PARAM_STR);
        $result->bindValue(':inputAnswer', $inputAnswer, PDO::PARAM_STR);
        

        
        try {
            $result->execute();
        }catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["success"] = 0;
            $response["message"] = $e->getMessage();
            echo json_encode($response);
            die();
            exit();
        }

        $response = array();
        
        $response["success"] = 1;
        $response["message"] = "answer submitted";
        
        echo json_encode($response);
        die();
        exit();
              
    } else{
        echo "";
    }

?>
