<?php
/** login authentication service **/
    include_once "dbconnect.php";
    session_start();
    $userStrutt = $conn->real_escape_string(filter_var($_POST["userStrutt"], FILTER_SANITIZE_STRING));
    $passStrutt = $conn->real_escape_string(filter_var($_POST["passStrutt"], FILTER_SANITIZE_STRING));
    $pin = $conn->real_escape_string($_POST["pin"]);
    $token = bin2hex(openssl_random_pseudo_bytes(36));
    sleep(2);
    if(empty($pin)){
        if(isset($userStrutt) && isset($passStrutt)){
            $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE username = '$userStrutt'";
            $b = $conn->query($a);
            $fetch= mysqli_fetch_array($b);
            if(mysqli_num_rows($b) == 1){
                if(password_verify($passStrutt,$fetch["password"])){
                    if($fetch['email_verification']!= 1){
                        header ('LOCATION: https://strutt.herokuapp.com/login.php?notverify='.urlencode(1));
                        error_log("Unverified account login attempt by: ".$userStrutt." on: ".date("F j, Y, g:i a"));
                        exit;
                    }
                    else{
                        if ($fetch['login_attempts'] == 5) {
                            if (time() < (strtotime($fetch['lockout_time']))){
                                header('LOCATION: https://strutt.herokuapp.com/login.php?lockout='.urlencode(1));
                            }
                            else{
                                $d = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET login_attempts = '0', lockout_time = '0', token = '$token', login_time = NOW() WHERE username = '$userStrutt'";
                                $e = $conn->query($d);
                                $_SESSION['username']= $fetch['username'];
                                $_SESSION['authToken'] = $token;
                                $_SESSION['logged_in']=true;
                                $_SESSION['last_activity']=time();
                                $_SESSION['expire_time']= 5*60;
                                $_SESSION['expire'] = time() + (30*60);
                                //setcookie("authToken",$token, time()+ (30 * 60),'/');
                                error_log("Account login success by: ".$userStrutt." on: ".date("F j, Y, g:i a"));
                                header ("LOCATION: https://strutt.herokuapp.com/");
                            }
                        }
                        else{
                            $d = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET login_attempts = '0', lockout_time = '0', token = '$token', login_time = NOW() WHERE username = '$userStrutt'";
                            $e = $conn->query($d);
                            $_SESSION['username']= $fetch['username'];
                            $_SESSION['authToken'] = $token;
                            $_SESSION['logged_in']=true;
                            $_SESSION['last_activity']=time();
                            $_SESSION['expire_time']= 5*60;
                            $_SESSION['expire'] = time() + (30*60);
                            //setcookie("authToken",$token, time()+ (30 * 60),'/');
                            error_log("Account login success by: ".$userStrutt." on: ".date("F j, Y, g:i a"));
                            header ("LOCATION: https://strutt.herokuapp.com/");
                        }
                    }
                }
                else{ 
                    if($fetch['login_attempts'] < 5){
                        $f = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET login_attempts = login_attempts+1 WHERE username = '$userStrutt'";
                        $conn->query($f);

                        $a2 = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE username = '$userStrutt'";
                        $b2 = $conn->query($a2);
                        $fetch2= mysqli_fetch_array($b2);

                        if($fetch2['login_attempts'] == 5){
                            $h = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET lockout_time = DATE_ADD(NOW(),INTERVAL 2 MINUTE) WHERE username = '$userStrutt'";
                            $conn->query($h);
                            error_log("Account lockout on: ".$userStrutt." on: ".date("F j, Y, g:i a"));
                            header ('LOCATION: https://strutt.herokuapp.com/login.php?lockout='.urlencode(1)); exit;
                        }
                        error_log("Account login failure on ".$userStrutt." on: ".date("F j, Y, g:i a"));
                        header ('LOCATION: https://strutt.herokuapp.com/login.php?error='.urlencode(1)); exit;
                    }
                    else{ header ('LOCATION: https://strutt.herokuapp.com/login.php?lockout='.urlencode(1)); exit; }
                }
            }
            else{ 
                error_log("Login user find error on: ".date("F j, Y, g:i a"));
                header ('LOCATION: https://strutt.herokuapp.com/login.php?notfound='.urlencode(1)); exit; 
            }
        }
        else{
            error_log("Empty login form submission on: ".date("F j, Y, g:i a"));
            header ('LOCATION: https://strutt.herokuapp.com/login.php?empty='.urlencode(1)); exit; 
        }
    }
    else{
        header ('LOCATION: https://strutt.herokuapp.com/login.php');
    }
?>