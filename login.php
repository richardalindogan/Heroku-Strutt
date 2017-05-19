<!DOCTYPE html>
<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("LOCATION:https://strutt.herokuapp.com/");
    }
?>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Oleo+Script|Nova+Flat" rel="stylesheet">
        <link href="css/bootstrap_pure.css" type="text/css" rel="stylesheet"/>
        <link href="css/flat-ui.css" type="text/css" rel="stylesheet"/>
        <link href="css/format_login.css" type="text/css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/flat-ui.js"></script>
        <title>Strutt | Login</title>
    </head>
    <body>
       <?php 
             $error = $_GET['error'];
             $notverify = $_GET['notverify'];
             $notfound = $_GET['notfound'];
             $lockout = $_GET['lockout'];
                 
            if(filter_var($error, FILTER_VALIDATE_INT) == true &&isset($error)&& $error = 1){
                     echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Password was incorrect!</p></div>';
            }
            elseif(filter_var( $notverify, FILTER_VALIDATE_INT) == true && isset($notverify)&& $notverify = 1){
                 echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">User not verified!</p></div>';
            }
            elseif(filter_var($notfound, FILTER_VALIDATE_INT) == true && isset($notfound)&& $notfound = 1){
                 echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">User not found!</p></div>';
            } 
            elseif(filter_var($lockout, FILTER_VALIDATE_INT) == true && isset($lockout)&& $lockout = 1){
                 echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">You are locked out!</p></div>';
            } 
                
        ?>
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <a href="index.php">Strutt</a>
                    <p>Sign In</p> 
                </div> 
            </div>
            <div id="register_form" class="row">
              <form action="Services/authenticate.php" method="post">
                    <div class="form-group center-block">
                        <label>Username</label>
                        <input type="text" name="userStrutt" class="form-control" aria-describedby="sizing-addon2" required/>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="passStrutt" class="form-control" aria-describedby="sizing-addon2" required/>
                        <input type="hidden" name="pin" class="form-control" />
                        <div style="font-size:15px; padding-top:5px; "><a href="reset.php">Forgot your password?</a></div>
                    </div>
                    <button class="btn btn-inverse" type="submit">Login</button>
                </form>
            </div>
        </div>
    </body>
</html>