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
    $email = isset($_POST['inputEmail']) ? $_POST['inputEmail'] : null;
    $password = isset($_POST['inputPassword']) ? $_POST['inputPassword'] : null;
    $firstName = (isset($_POST['inputFirstName']) ? $_POST['inputFirstName'] : null);
    $lastName = (isset($_POST['inputLastName']) ? $_POST['inputLastName'] : null);

    $classTitle = isset($_POST['inputClassTitle']) ? $_POST['inputClassTitle'] : null;
    $currentPage = isset($_POST['inputCurrentPageNumber']) ? $_POST['inputCurrentPageNumber'] : null;
    $resultsPerPage = isset($_POST['inputResultsPerPage']) ? $_POST['inputResultsPerPage'] : null;
    $pictureContent = isset($_FILES['inputUserPicture']) ? $_FILES['inputUserPicture'] : null;
    

    $fields = array();
    $postvars;
    
    
    switch($methodName){
            
        //add as many necessary method invocations
        case "attemptLogin":
           
            //$url = "http://192.168.1.57/pages/test.php";
            //$url = "http://wolfpack.cs.hbg.psu.edu/pages/test.php"
            $url = "http://192.168.1.57/pages/Sign_in_student.php";
            
            $fields = build_fields($fields, 
                         array("inputEmail", "inputPassword", "android"),
                        $email,
                        $password, 
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;
            
        case "findClasses":
            //$url = "http://wolfpack.cs.hbg.psu.edu/pages/Class_Search.php"
            $url = "http://192.168.1.57/pages/Class_Search_Stub.php";
            
            $fields = build_fields($fields,
                                   array('inputClassTitle', 'inputCurrentPageNumber','android'),
                                   $classTitle,
                                   $currentPage,
                                   $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

        case "uploadSinglePic":
            //$url = "http://wolfpack.cs.hbg.psu.edu/pages/Class_Search.php"
            $url = "http://192.168.1.57/pages/upload.php";
            
            $fields = build_fields($fields,
                                   array('inputUserPictureName', 'inputUserPictureContent','android'),
                                   $pictureContent["name"],
                                   $pictureContent["image"],
                                   $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

        default:
            $response = array();
            $response["message"] = "No valid use found";
            $response["success"] = 0;
            echo json_encode($response);
            exit(1);
            
            break;
            
    }
?>
