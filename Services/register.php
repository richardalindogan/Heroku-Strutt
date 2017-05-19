<?php
/** user registration service **/
    require('/app/vendor/autoload.php');
    include_once "dbconnect.php";
    $userStrutt = $conn->real_escape_string(filter_var($_POST["userStrutt"], FILTER_SANITIZE_STRING));
    $passStrutt = password_hash($conn->real_escape_string($_POST["passStrutt"]), PASSWORD_DEFAULT);
    $email = $conn->real_escape_string(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
    
    $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE username = '$userStrutt'";
    $b = $conn->query($a);
    if(mysqli_num_rows($b) > 0){
        error_log("New account creation attempt - Username:".$userStrutt." already used on: ".date("F j, Y, g:i a"));
        header ('LOCATION: https://strutt.herokuapp.com/signup.php?usererror='.urlencode(1));
    }
    else{
        $c = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE email = '$email'";
        $d = $conn->query($c);
        if(mysqli_num_rows($d) > 0){
            error_log("New account creation attempt - Email already used on: ".date("F j, Y, g:i a"));
            header ('LOCATION: https://strutt.herokuapp.com/signup.php?emailerror='.urlencode(1));
        }
        else{
            $e = "INSERT INTO heroku_28db52ced0c34d2.`strutt-users` (username,password,email) VALUES ('$userStrutt','$passStrutt','$email')";
            $f = $conn->query($e);
            $g = "INSERT INTO heroku_28db52ced0c34d2.`strutt-profile` (username) VALUES ('$userStrutt')";
            $h = $conn->query($g);
            
            //construct email
            $from = new SendGrid\Email(null, "strutt@heroku.com");
            $subject = "User Verification";
            $to = new SendGrid\Email(null, $email );
            $content = new SendGrid\Content("text/plain", "Hello ".$userStrutt.", verify your account here: https://strutt.herokuapp.com/Services/verify.php?u=". $userStrutt);
            $mail = new SendGrid\Mail($from, $subject, $to, $content);

            $apiKey = getenv('SENDGRID_API_KEY');
            $sg = new \SendGrid($apiKey);

            $response = $sg->client->mail()->send()->post($mail);
            error_log("New account registered: ".$userStrutt." on: ".date("F j, Y, g:i a"));
            header ('LOCATION: https://strutt.herokuapp.com/');
        }
    }
?>