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
            // set the url, number of POST vars, POST data
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
    

    $fields = array();
    $postvars;
    
    // to force output of url file back to our android client
    //curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

    switch($methodName){
            
        //add as many necessary method invocations
        case "attemptLogin":
           
            //$url = "http://192.168.1.57/pages/test.php";
            $url = "http://192.168.1.57/Sign_in_student.php";
            
            $fields = build_fields($fields, 
                         array("inputEmail", "inputPassword", "android"),
                        $email,
                        $password, 
                        $android);
            
            $postvars = http_build_query($fields);

            build_curlreq($fields, $postvars, $url);

            break;

        default:
            //echo '{"message": "default", "success": "0"}';
            break;
            
    }
?>
