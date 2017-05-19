<?php
    session_start();
    session_destroy();
    unset($_COOKIE['authToken']);
    error_log("Logout for user: ".$_SESSION['username']." on: ".date("F j, Y, g:i a"));
    header('LOCATION:  https://strutt.herokuapp.com/');
?>