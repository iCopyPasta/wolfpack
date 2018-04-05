<?php

    /*
    
    submits an answer for the first, or nth time given a live question for a live session

    expected POST variables:
    "inputStudentId"
    "inputClassId"
    
    return: JSON obj with the status of the 'insert'.

    Example Usage:
    ensure you are using POST variables for the aforementioned variables

    Example Return JSON Value:
    {"success":1,"message":"answer successfully submitted"}
    
    */

    function sanityCheck($studentId, $classId): int{
          // may be better to make connection in calling page
          $tempConn = new Connection;
          $pdo = $tempConn->getConnection();

          $sql = "SELECT student_id, class_id
                  FROM student_is_in
                  WHERE student_is_in.student_id = :student_id
                    AND student_is_in.class_id = :class_id";

          $stmt = $pdo->prepare($sql);
          $stmt->bindValue(':student_id', $studentId, PDO::PARAM_INT);
          $stmt->bindValue(':class_id', $classId,PDO::PARAM_INT);

          try{
            $stmt->execute();
            $r = $stmt->fetch(PDO::FETCH_NUM);
          }catch (Exception $e){
            return false;
          }finally{
              $tempConn = null;
          }
            return ($r > 0);
        }
    

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //in an ideal world, we'd perform sanitation!
        
        $inputStudentId = isset($_POST["inputStudentId"]) ? $_POST["inputStudentId"] : null;
        $inputClassId = isset($_POST["inputClassId"]) ? $_POST["inputClassId"] : null;
        
        include_once('Connection.php');
        $connection = new Connection;
        $pdo = $connection->getConnection();
        
        $proceed = sanityCheck($inputStudentId, $inputClassId);
        
        $sql = "DELETE FROM student_is_in
                WHERE student_is_in.student_id = :inputStudentId AND
                student_is_in.class_id = :inputClassId;";
        $result = $pdo->prepare($sql);
        $result->bindValue(':inputStudentId', $inputStudentId, PDO::PARAM_INT);
        $result->bindValue(':inputClassId', $inputClassId, PDO::PARAM_INT);
        
        try {
            
            if($proceed){
                $worked = $result->execute();
                if($worked){
                    $response = array();
                    $response["success"] = 1;
                    $response["message"] = "class successfully dropped";
                    echo json_encode($response);

                }
                else{
                    $response = array();
                    $response["success"] = 0;
                    $response["message"] = "did not drop class";
                    echo json_encode($response);
                }
            }
            else{
                $response = array();
                $response["success"] = 0;
                $response["message"] = "did not find student id or class id";
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
