<?php
    //echo"<p>someHTMLbb</p>";
    $android = true;
    $email = isset($_POST['inputEmail']) ? $_POST['inputEmail'] : null;
    $password = isset($_POST['inputPassword']) ? $_POST['inputPassword'] : null;
    //echo '{"message": "hello from test.php", "success":"1"}';

    echo '{"message": "success from test.php: '.$email.", ".$password.", ".$android.'","success": "1"}';
?>
