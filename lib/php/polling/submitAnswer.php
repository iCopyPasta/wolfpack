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
    {"success":1,"message":"answer successfully submitted"}
    
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputStudentId = isset($_POST["inputStudentId"]) ? (int) $_POST["inputStudentId"] : null;
        $inputSessionId = isset($_POST["inputSessionId"]) ? (int) $_POST["inputSessionId"] : null;
        
        $inputQuestionHistoryId = isset($_POST["inputQuestionHistoryId"]) ? (int) $_POST["inputQuestionHistoryId"] : null;
        
        $inputAnswerType = isset($_POST["inputAnswerType"]) ? $_POST["inputAnswerType"] : null;
        $inputAnswer = isset($_POST["inputAnswer"]) ? $_POST["inputAnswer"] : null;
        
        include_once('../Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        $sql = "REPLACE INTO answers (student_id, session_id, question_history_id, answer_type, answer)
                    VALUES(:inputStudentId, :inputSessionId, :inputQuestionHistoryId, :inputAnswerType,  :inputAnswer);
                    ";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputStudentId', $inputStudentId, PDO::PARAM_INT);
        $result->bindValue(':inputSessionId', $inputSessionId, PDO::PARAM_INT);
        $result->bindValue(':inputQuestionHistoryId', $inputQuestionHistoryId, PDO::PARAM_INT);
        $result->bindValue(':inputAnswerType', $inputAnswerType, PDO::PARAM_STR);
        $result->bindValue(':inputAnswer', $inputAnswer, PDO::PARAM_STR);
        
        try {
            $worked = $result->execute();
            
            if($worked){
                $response = array();
                $response["success"] = 1;
                $response["message"] = "answer successfully submitted";
                echo json_encode($response);

            }
            else{
                $response = array();
                $response["success"] = 0;
                $response["message"] = "did not submit answer";
                echo json_encode($response);
            }
        }catch (Exception $e) {
            // fail JSON response
            $response = array();
            $response["success"] = 0;
            $response["message"] = $e->getMessage();
            echo json_encode($response);
            die();
            exit();
        }
        die();
        exit();
              
    }

?>
