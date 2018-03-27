<?php
    function build_fields($required) {
        
        // Contains all arguments that come after $required
        // as they were present at call time
        $args = array_slice(func_get_args(), 1);
        $strings_for_post = (array) $args[0];

        //construct an array with a mapping from POST string key field to actual value we've obtained   
        return array_combine($strings_for_post, array_slice(func_get_args(), 2));
    
    }

    function build_curlreq($fields, $postvars, $url){
            
            
            $ch = curl_init();
            // to force output of url file back to our android client
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

            $result = curl_exec($ch);

            // if the result was successful
            if($result !== null && is_string($result)){
                
                echo $result;
            }

            else{
                echo '{"message": "failure execute_curl_api", "success": "0"}';
            }

            // close connection
            curl_close($ch);
        
    }

    //list of all aggregated POST variables for android usage
    $android = true;
    $methodName = isset($_POST['inputMethodName']) ? $_POST['inputMethodName'] : null;

    //user information
    $email = isset($_POST['inputEmail']) ? $_POST['inputEmail'] : null;
    $password = isset($_POST['inputPassword']) ? $_POST['inputPassword'] : null;
    $confirmPassword = isset($_POST['inputConfirmPassword']) ? $_POST['inputConfirmPassword'] : null;
    $userTitle = isset($_POST["inputUserTitle"]) ? $_POST["inputUserTitle"]: null;
    $firstName = (isset($_POST['inputFirstName']) ? $_POST['inputFirstName'] : null);
    $lastName = (isset($_POST['inputLastName']) ? $_POST['inputLastName'] : null);
   
    //search information
    $classTitle = isset($_POST['inputClassTitle']) ? $_POST['inputClassTitle'] : null;

    //pagination variables
    //too lazy to go and fix other usage in searchClassesForStudent.php
    $currentPage = isset($_POST['inputCurrentPage']) ? $_POST['inputCurrentPage'] : null;
    $currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : $currentPage;

    $resultsPerPage = isset($_POST['inputResultsPerPage']) ? $_POST['inputResultsPerPage'] : null;

    //too lazy to go and fix other usage in searchClassesForStudent.php
    $rowsPerPage = isset($_POST['inputRowsPerPage']) ? $_POST['inputRowsPerPage'] : null;
    $rowsPerPage = isset($_POST['rowsPerPage']) ? $_POST['rowsPerPage'] : $rowsPerPage;

    //polling variables
    $inputStudentId = isset($_POST['inputStudentId']) ? $_POST['inputStudentId'] : null;
    $inputClassId = isset($_POST['inputClassId']) ? $_POST['inputClassId'] : null;
    $inputQuestionSetId = isset($_POST['inputQuestionSetId']) ? $_POST['inputQuestionSetId'] : null;
    $inputQuestionId = isset($_POST['inputQuestionId']) ? $_POST['inputQuestionId'] : null;
    $inputSessionId = isset($_POST['inputSessionId']) ? $_POST['inputSessionId'] : null;
    $inputQuestionHistoryId = isset($_POST['inputQuestionHistoryId']) ? $_POST['inputQuestionHistoryId'] : null;
    $inputAnswerType = isset($_POST['inputAnswerType']) ? $_POST['inputAnswerType'] : null;
    $inputAnswer = isset($_POST['inputAnswer']) ? $_POST['inputAnswer'] : null;


    //misc
    $pictureContent = isset($_FILES['inputUserPicture']) ? $_FILES['inputUserPicture'] : null;
    $debugResponse = array();
    
    
    //camera debugging
    /*if($pictureContent !== null){
        $debugResponse["message"] = "pictureContent is NOT null : ";
        
    }*/

    /*if($methodName !== null){
        $debugResponse["message"] = $debugResponse["message"] . "inputMethodName is NOT null";
        $debugResponse["success"] = 1;
        echo json_encode($debugResponse);
        exit(1);
    }*/

    
    $fields = array();
    $postvars;
    
    
    switch($methodName){
            
        //add as many necessary method invocations
        case "attemptLoginStudent":
           
            
            $url = "http://wolfpack.cs.hbg.psu.edu/pages/Sign_in_student.php";
            //$url = "http://192.168.1.57/pages/Sign_in_student.php";
            
            $fields = build_fields($fields, 
                         array("inputEmail", "inputPassword", "android"),
                        $email,
                        $password, 
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
        case "attemptSignUp":
           
            $url = "http://wolfpack.cs.hbg.psu.edu/pages/Sign_up.php";
            //$url = "http://wolfpack.cs.hbg.psu.edu/pages/Sign_up_student.php"; <-- anticipatory
            //$url = "http://192.168.1.57/pages/Sign_up.php";
            
            $fields = build_fields($fields, 
                         array("inputEmail", "inputPassword", "inputUserTitle","inputConfirmPassword","inputFirstName","inputLastName","android"),
                        $email,
                        $password, 
                        $userTitle,
                        $confirmPassword,
                        $firstName,
                        $lastName,
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
        case "findClassesToAdd":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/searchClassesByTitleAndName.php";
            //$url = "http://192.168.1.57/lib/php/searchClassesByTitleAndName.php";
            
            $fields = build_fields($fields,
                                   array('title', 'firstName','lastName','currentPage','rowsPerPage'),
                                   $classTitle,
                                   $firstName,
                                   $lastName,
                                   $currentPage,
                                   $rowsPerPage
                                   );
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
        case "enrollForClass":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/enrollForClass.php";
            //$url = "http://192.168.1.57/lib/php/enrollForClass.php";
            
            $fields = build_fields($fields,
                                   array('inputStudentId', 'inputClassId',"android"),
                                   $inputStudentId,
                                   $inputClassId,
                                   $android
                                   );
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;

        case "uploadSinglePic":
  
            if (move_uploaded_file($_FILES["inputUserPicture"]["tmp_name"], 
        "/var/www/html/images/". $_FILES["inputUserPicture"]["name"])) {
                $response["message"] ="The file has been uploaded";
                $response["success"] = 1;
                echo json_encode($response);
                exit(1);
            }            
            else{
                $response["message"] ="The file has not been uploaded";
                $response["success"] = 0;
                echo json_encode($response);
                exit(1);
            }
            exit(1);

            break;

	     case "findEnrolledClasses":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/searchClassesForStudent.php";
	
            $fields = build_fields($fields,
	                               array("currentPage", "rowsPerPage","student_id","android"),
                                   $currentPage,
                                   $rowsPerPage,
                                   $student_id,
	                               $android);

            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);
            break;
	    
        default:
            $response = array();
            $response["message"] = "No valid use found";
            $response["success"] = 0;
            echo json_encode($response);
            exit(1);
            
            break;
            
        case "searchActiveSession":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/searchActiveSession.php"
            //$url = "http://192.168.1.57/lib/php/polling/searchActiveSession.php";
	
            $fields = build_fields($fields,
	                           array("inputClassId","android"),
                                   $inputClassId,
	                               $android);

            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);
            break;
            
        case "searchActiveQuestion":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/polling/searchActiveQuestion.php";
            //$url = "http://192.168.1.57/lib/php/polling/searchActiveQuestion.php";
            
            $fields = build_fields($fields, 
                         array("inputQuestionSetId","android"),
                        $inputQuestionSetId,
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
        case "searchLiveQuestionInfo":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/polling/searchLiveQuestionInfo.php";
            //$url = "http://192.168.1.57/lib/php/polling/searchLiveQuestionInfo.php";
            
            $fields = build_fields($fields, 
                         array("inputQuestionId","android"),
                        $inputQuestionId,
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
        case "submitAnswer":
            $url = "http://wolfpack.cs.hbg.psu.edu/lib/php/polling/submitAnswer.php";
            //$url = "http://192.168.1.57/lib/php/polling/submitAnswer.php";
            
            $fields = build_fields($fields, 
                         array("inputStudentId",
                               "inputSessionId",
                               "inputQuestionHistoryId",
                               "inputAnswerType",
                               "inputAnswer",
                               "android"),
                        $inputStudentId,
                        $inputSessionId,
                        $inputQuestionHistoryId,
                        $inputAnswerType,
                        $inputAnswer,
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
    }
?>

