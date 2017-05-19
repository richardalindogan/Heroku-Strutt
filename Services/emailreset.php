<?php
/** login service to reset a password and send user an email **/
    require('/app/vendor/autoload.php');
    include_once "dbconnect.php";
    $userEmail = $conn->real_escape_string($_POST['userEmail']);
    
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $temp_password = '';
    for ($i = 0; $i < 12; $i++) {
        $temp_password .= $characters[rand(0, $charactersLength - 1)];
    }

    $passStrutt = password_hash($temp_password, PASSWORD_DEFAULT);
    
    $test="SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE email = '$userEmail'";
    $query = $conn->query($test);
    $fetch = mysqli_fetch_array($query);
    $rowcount=mysqli_num_rows($query);
    if($rowcount == 1){
        $a = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET password ='$passStrutt' WHERE email = '$userEmail'";
        $conn->query($a);

        //construct email
        $from = new SendGrid\Email(null, "strutt@heroku.com");
        $subject = "Password Reset";
        $to = new SendGrid\Email(null, $userEmail );
        $content = new SendGrid\Content("text/plain", "Hello ".$fetch['username'].", your password has been reset to: ". $temp_password);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        error_log("Password reset for:".$fetch['username']." on: ".date("F j, Y, g:i a"));
        header('LOCATION: https://strutt.herokuapp.com/login.php');
    }
    else{
        echo"failed";
        header('refresh:3; url=https://strutt.herokuapp.com/login.php');
    }

   
?>