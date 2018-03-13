

<?php
  /*
    function searchClassesByStudent
    arguments:  page - the current page
                rowsPerPage - rows per page
                student_id - students id number
    return: JSON obj containing a list of classes the student belongs to

    Example Usage:
    $search = searchClassesByStudent(1, 10, 1);

    Example Return Value:
    [{"message":"Success SELECTING from class_course, has, class_section, is_in, student_account","success":1},
      {"class_id":"1","class_course_number":"460","location":"Olmstead 218","offering":"offering?","title":"Principles of Programming","section_id":"1","class_section_number":"1"}]

  */
  function searchClassesByStudent($page, $rowsPerPage, $student_id){
    include_once('Connection.php');
    $connection = new Connection;
    $pdo = $connection->getConnection();

    $sql = "SELECT  class_course.class_id, class_course.class_course_number, class_course.location,
                    class_course.offering, class_course.title,
                    class_section.section_id, class_section.class_section_number, class_section.offering,
                    class_section.location
            FROM class_course, has, class_section, is_in, student_account
            WHERE student_account.student_id = is_in.student_id
              AND is_in.class_id = class_course.class_id
              AND class_course.class_id = has.class_id
              AND has.section_id = class_section.section_id
              AND student_account.student_id = :student_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':student_id', $student_id);

    try{
      // $stmt->execute(['email' => $this->email, 'password' => $this->password]);
      $stmt->execute();
    }catch (Exception $e){
      // fail JSON response
      $response = array();
      $response["message"] = "ERROR SELECTING: ".$e->getMessage();
      $response["success"] = 0;
      // echo json_encode($response);
      // die();
      return json_encode($response);
    }

    $pdo = null;
    $response = array();
    $response["message"] = "Success SELECTING from class_course, has, class_section, is_in, student_account";
    $response["success"] = 1;
    $retVal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    array_unshift($retVal, $response);
    return json_encode($retVal);
  }

?>