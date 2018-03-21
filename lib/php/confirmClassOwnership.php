<?php
/*
 * Provides the ability to check if a teacher really teaches a class, used for security checks
*/

include_once('..\lib\php\Connection.php');
    
    function confirmClassOwnership($class_id,$teacher_id){
    
      $connection = new Connection;
      $pdo = $connection->getConnection();
        
    
      $sql = "SELECT * FROM teaches WHERE class_id = '$class_id' AND teacher_id = $teacher_id";
      $stmt = $pdo->prepare($sql);
      try{
        $stmt->execute();
      }
      catch (Exception $e){
        // fail JSON response
        echo $e->getMessage();

        die();
      }
        
        $pdo = null;
        
        
        $result = $stmt->fetch(); //loads in result
    
        if (!isset($result['teacher_id'])) {
            return false;
        }
        
        return true;
    }

    

?>