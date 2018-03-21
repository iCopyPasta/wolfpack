<?php
    $android = isset($_POST["android"]) ? $_POST["android"] : null;
    $classTitle = isset($_POST['inputClassTitle']) ? $_POST['inputClassTitle'] : null;
    $currentPage = isset($_POST['inputCurrentPageNumber']) ? $_POST['inputCurrentPageNumber'] : 1;
    $resultsPerPage = isset($_POST['inputResultsPerPage']) ? $_POST['inputResultsPerPage'] : null;


    #dummy test values for now while Scott is working on other scripts

    $Section1 = array("location" => "Olmstead", 
                      "section_id" => 10542,
                      "offering" => "Spring 2018",
                      "class_title" => "CMPSC 122",
                      "class_section_number" => 1);
        
    $Section2 = array("location" => "Olmstead", 
                      "section_id" => 10543,
                      "offering" => "Spring 2018",
                      "class_title" => "CMPSC 122",
                      "class_section_number" => 2);

    $Section3 = array("location" => "Olmstead", 
                      "section_id" => 10544,
                      "offering" => "Spring 2018",
                      "class_title" => "CMPSC 122",
                      "class_section_number" => 3);

    $Section4 = array("location" => "Olmstead", 
                          "section_id" => 10545,
                          "offering" => "Spring 2018",
                          "class_title" => "CMPSC 122",
                          "class_section_number" => 4);

    $Section5 = array("location" => "Olmstead", 
                          "section_id" => 10546,
                          "offering" => "Spring 2018",
                          "class_title" => "CMPSC 122",
                          "class_section_number" => 5);

    $Section6 = array("location" => "Olmstead", 
                              "section_id" => 10547,
                              "offering" => "Spring 2018",
                              "class_title" => "CMPSC 122",
                              "class_section_number" => 6);

    $Section7 = array("location" => "Olmstead", 
                              "section_id" => 10548,
                              "offering" => "Spring 2018",
                              "class_title" => "CMPSC 122",
                              "class_section_number" => 7);

    $Section8 = array("location" => "Olmstead", 
                              "section_id" => 10549,
                              "offering" => "Spring 2018",
                              "class_title" => "CMPSC 122",
                              "class_section_number" => 8);

    $Section9 = array("location" => "Olmstead", 
                              "section_id" => 10550,
                              "offering" => "Spring 2018",
                              "class_title" => "CMPSC 122",
                              "class_section_number" => 9);


    if($currentPage <= 1){
        $retVal = array();
        $retVal["currentPage"] = 1;
        $retVal["totalResults"] = 9;
        $retVal["totalPages"] = 2;
        $retVal["results"] = array($Section1,
                                 $Section2,
                                 $Section3,
                                 $Section4,
                                 $Section5);
        
        echo json_encode($retVal);
    }
    else{
        $retVal = array();
        $retVal["currentPage"] = 1;
        $retVal["totalResults"] = 9;
        $retVal["totalPages"] = 2;
        $retVal["results"] = array($Section6,
                                 $Section7,
                                 $Section8,
                                 $Section9);
        
        echo json_encode($retVal);
        
    }


    
?>