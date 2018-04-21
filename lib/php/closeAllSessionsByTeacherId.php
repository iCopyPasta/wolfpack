<?php
/*
 * Provides the ability to deactivate all sessions and questions running for a teacher, used to flush sessions when not on poll page.
*/

include_once('../lib/php/Connection.php');
    
    function closeAllSessions($teacher_id){
    
      $connection = new Connection;
      $pdo = $connection->getConnection();
        
       //removes all active questions related to teacher
      $sql = "DELETE FROM active_question WHERE question_set_id IN
                ( SELECT question_set_id FROM active_question_set
                    WHERE class_id IN
                        ( SELECT class_id FROM teaches
                            WHERE teaches.teacher_id = $teacher_id
                            )
                    );
                ";
      $stmt = $pdo->prepare($sql);
      try{
        $stmt->execute();
      }
      catch (Exception $e){
        // fail JSON response
        echo $e->getMessage();

        die();
      }
      
      
      
      $sql = "
                 DELETE FROM active_question_set
                    WHERE class_id IN
                        ( SELECT class_id FROM teaches
                            WHERE teaches.teacher_id = $teacher_id
                            )
                    ;
                ";
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
  
    }

    

?>
