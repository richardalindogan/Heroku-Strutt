<?php
    include_once "dbconnect.php";
    $user = $conn->real_escape_string($_GET['u']);
    $a = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET email_verification = '1' WHERE username = '$user'";
    $conn->query($a);
    error_log("User account verified:".$user." on: ".date("F j, Y, g:i a"));
    header("LOCATION: https://strutt.herokuapp.com/index.php?verified=1");
?>