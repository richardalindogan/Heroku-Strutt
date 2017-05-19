<!DOCTYPE html>
<?php
    session_start();
    if(isset($_SESSION['username'])){
                header ('LOCATION: /~s0876956/Strutt/index');
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
        <title>Strutt | Reset</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <a href="index.php">Strutt</a>
                    <p>Password Reset</p> 
                </div> 
            </div>
            <div id="register_form" class="row">
              <form action="Services/emailreset.php" method="post">
                    <div class="form-group center-block">
                        <label>Email</label>
                        <input type="email" name="userEmail" class="form-control" aria-describedby="sizing-addon2" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required/>
                    </div>
                    <button class="btn btn-inverse" type="submit">Reset</button>
                </form>
            </div>
        </div>
    </body>
</html>