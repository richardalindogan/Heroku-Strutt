<?php
/** Checks for user session data and authentication token **/
    $userStrutt = $_SESSION['username'];
    include_once "Services/dbconnect.php";
    $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE username = '$userStrutt'";
    $b = $conn->query($a);
    $fetch= mysqli_fetch_array($b);
    if(mysqli_num_rows($b) == 1){
          if($_SESSION['authToken'] != $fetch['token']){
            error_log("Authentication token mismatch! for user: ".$userStrutt." on: ".date("F j, Y, g:i a"));
            error_log("Session destroy for user: ".$userStrutt." on: ".date("F j, Y, g:i a"));
            session_destroy();
          }
    }
    else{
        session_destroy();
    }
?>