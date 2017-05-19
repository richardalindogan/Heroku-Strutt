<?php
/** Verifies that the user before going to their profile**/
    include_once "Services/dbconnect.php";
    $userStrutt = $_SESSION['username'];
    $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE username = '$userStrutt'";
    $b = $conn->query($a);
    $fetch= mysqli_fetch_array($b);
    if(!isset($_SESSION['username'])){
         header ('LOCATION: https://strutt.herokuapp.com/');
    }
    else{
        if($_SESSION['authToken'] != $fetch['token']){
            error_log("Authentication token mismatch! Profile access denied for user: ".$userStrutt." on: ".date("F j, Y, g:i a"));
            session_destroy();
            header ('LOCATION: https://strutt.herokuapp.com/');
        }
    }
?>