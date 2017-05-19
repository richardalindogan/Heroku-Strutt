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
        <title>Register | Strutt</title>
    </head>
    <body>
        <?php 
            if(isset($_GET['usererror'])&& $_GET['usererror'] = 1){
                    echo '<p style="color: red">"User already exists!"</p>';
            }
            elseif(isset($_GET['emailerror'])&& $_GET['emailerror'] = 1){
                echo '<p style="color: red">"Email already used!"</p>';
            }   
        ?>
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <a href="index.php">Strutt</a>
                    <p>Register</p> 
                </div> 
            </div>
            <div id="register_form"class="row">
                <!--action redirect to an account creation service-->
                <form action="Services/register.php" method="post">
                    <div class="form-group center-block">
                        <label>Username</label>
                        <input type="text" name="userStrutt" class="form-control" aria-describedby="sizing-addon2" required/>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="passStrutt" id="password" class="form-control" aria-describedby="sizing-addon2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" aria-describedby="sizing-addon2" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required/>
                    </div>
                    <button class="btn btn-default" type="submit">Sign Up</button>
                </form>
             </div>
        </div>
    </body>
</html>