<?php

    //ANDROID-SPECIFIC LOGIC AND USAGE
    $android_file_check = require_once "androidAPI.php";

    if(!$android_file_check){
        $response["message"] = "Sorry, critical error occured on server";
        $response["success"] = 0;
        echo json_encode($response);
        exit(1);
    }

    $response = array();
    $response["message"] = "In upload.php after first check";
    $response["success"] = 1;
    echo json_encode($response);
    exit(1);

    $pictureContent = isset($_POSTS['inputUserPictureContent']) ? $_POST['inputUserPictureContent'] : null;
    $android = isset($_POST['android']) ? $_POST['android'] : false;
    

    #CHANGE BASED ON SERVER USED
    #$target_dir = "/var/www/html/images/";
    
    $target_dir = "/var/www/html/images/";
    $target_file = $target_dir . $pictureContent["name"];
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    /*if(isset($_POST["??"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $response["message"] = "File is not an image.";
            $response["success"] = 0;
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $response["message"] = "File is not an image.";
                $response["success"] = 0;
            echo json_encode($response);
            exit(1);
        }
    }*/
    // Check if file already exists
    if (file_exists($target_file)) {
        $response["message"] = "Sorry, file already exists.";
        $response["success"] = 0;
        echo json_encode($response);
        exit(1);
    }
    /*// Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $respone["message"] = "Sorry, your file is too large.";
        $response["success"] = 0;
        echo json_encode($response);
        exit(1);
    }*/
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "jpeg") {
        $response["message"] = "Sorry, only JPG or JPEG types";
        $response["success"] = 0;
        echo json_encode($response);
        exit(1);
    }

    if (move_uploaded_file($pictureContent, $target_file)) {
        $respone["message"] ="The file has been uploaded";
        $response["success"] = 0;
        echo json_encode($response);
        exit(1);
    } else {
        $response["message"] = "Sorry, there was an error uploading your file";
        $response["success"] = 0;
        echo json_encode($response);
        exit(1);
    }
    //END ANDROID-SPECIFIC USAGE

?>
