<?php

    /*
    
    returns a question_set_id and question_session_id for a live polling session.

    expected POST variables:
    "inputStudentId" - the student_id of the student enrolling in a class
    "inputClassId" - the given class_id you want to know for which a student wishes to enroll
               
    return: JSON obj containing a success status and message.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    
    */
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputStudentId = isset($_POST["inputStudentId"]) ? (int) $_POST["inputStudentId"] : null; 
        $inputClassId = isset($_POST["inputClassId"]) ? (int) $_POST["inputClassId"] : null;        
        
        include_once('Connection.php');
        include_once('isIdExistFunctions.php');
        
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        /*$isStudentIdExist = isStudentIdExist($inputStudentId);
        $isClassIdExist = isClassIdExist($inputClassId);*/
        
        //if($isStudentIdExist && $isClassIdExist) {
            $sql = "IF EXISTS (
                            SELECT * 
                            FROM student_is_in 
                            WHERE student_is_in.student_id = :inputStudentId
                            AND student_is_in.class_id = :inputClassId 
                            )
                        THEN UPDATE student_is_in 
                        SET class_id = :inputClassId 
                        WHERE student_id = :inputStudentId;

                        ELSE
                        INSERT INTO student_is_in
                                  (student_id, class_id)
                                  VALUES (:inputStudentId, :inputClassId);
                                  END IF";

            $result = $pdo->prepare($sql);
            $result->bindValue(':inputStudentId', $inputStudentId, PDO::PARAM_INT);
            $result->bindValue(':inputClassId', $inputClassId, PDO::PARAM_INT);    
            
            try {
                $worked = $result->execute();
                if($worked){
                    $response["success"] = 1;
                    $response["message"] = "enrolled in class";
                    echo json_encode($response);
                    
                }
                else{
                    $response = array();
                    $response["success"] = 0;
                    $response["message"] = "did not enroll";
                    echo json_encode($response);
                }
            }catch (Exception $e) {
                // fail JSON response
                $response = array();
                $response["success"] = 0;
                $response["message"] = $e->getMessage();
                echo json_encode($response);
                exit();
            }
            

            

        /*} else{
            $response = array();
            $response["success"] = 0;
            $response["message"] = "fatal error";
            echo json_encode($response);
            
        }*/

    }

?>
