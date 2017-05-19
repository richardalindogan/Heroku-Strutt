<!DOCTYPE html>
<?php
    session_start();
    include_once ('Scripts/profile_authenticate.php');
    include_once('Scripts/expiresession.php');
    include_once "dbconnect.php";
    $_SESSION['formToken']= bin2hex(openssl_random_pseudo_bytes(36));
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Oleo+Script" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="css/jasny-bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/flat-ui.css" type="text/css" rel="stylesheet"/>
        <link href="css/format.css" type="text/css" rel="stylesheet"/>
        <link href="css/modal.css" type="text/css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="js/jasny-bootstrap.min.js"></script>
        <title><?php echo $_SESSION['username']?> | Edit</title>
    </head>
    <body>
        <?php include('Scripts/postModal.php')?>
        <div id="wrapper">
            <?php include('navbar.php')?>
            <?php    
                $error =$_GET['error'];            
                $size = $_GET['sizelimit'];            
                $format = $_GET['formaterror'];            
                $verify = $_GET['verifyerror'];            
                $empty = $_GET['empty'];            
                $success = $_GET['success'];            
    
                if(filter_var($error, FILTER_VALIDATE_INT) == true &&isset($error)&& $error = 1){
                    echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">File upload was unsuccessful!</p></div>';
                }
                if(filter_var($size, FILTER_VALIDATE_INT) == true &&isset($size)&& $size = 1){
                    echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">File is too big! 1MB - Icon 25MB -Wallpaper</p></div>';
                }
                if(filter_var($format, FILTER_VALIDATE_INT) == true &&isset($format)&& $format = 1){
                    echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Only jpg, png, and jpeg files are allowed!</p></div>';
                }
                if(filter_var($verify, FILTER_VALIDATE_INT) == true &&isset($verify)&& $verify = 1){
                    echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Old Password didn not match record</p></div>';
                }
                elseif(filter_var($empty, FILTER_VALIDATE_INT) == true &&isset($empty)&& $empty = 1){
                    echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Form is empty!</p></div>';
                }
                elseif(filter_var($success, FILTER_VALIDATE_INT) == true &&isset($success)&& $success = 1){
                    echo '<div class="alert alert-success alert-dismissable"><p style="text-align: center">Profile update successful!</p></div>';
                } 
            ?>
            <div class="container">
                 <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#menu1">Appearance</a></li>
                        <li><a data-toggle="tab" href="#menu2">Password Reset</a></li>
                  </ul>
                  <div class="tab-content">
                    <div id="menu1" class="tab-pane fade in active">
                        <form action="Services/updateProfile.php" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label>Avatar Icon</label>
                                <input type="file" name="uIcon" id="uIcon" accept="image/jpeg|image/png"></input>
                            </div>

                            <div class="form-group">
                                <label>Profile Background</label>
                                <input type="file" name="proBack" id="uIcon" accept="image/jpeg|image/png"></input>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="profileDescription" rows="5" id="post-description" placeholder="100 words or less" maxlength="750"><?php
                                        $user = $_SESSION['username'];
                                        $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$user'";
                                        $b = $conn->query($a);
                                        $fetch= mysqli_fetch_array($b);
                                        echo htmlspecialchars($fetch['description']);
                                    ?></textarea>
                                <input type="hidden" name="cake" value="<?php echo $_SESSION['formToken']; ?>" class="form-control"/>
                            </div>
                                
                            <button class="btn btn-inverse" type="submit">Update</button>
                        </form>
                    </div>
                    <div id="menu2" class="tab-pane fade">

                        <!--Password update-->
                        <div class="form-group">
                            <form action="Services/updateProfile.php" method="post">
                                 <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="oldPass" id="password" class="form-control" aria-describedby="sizing-addon2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
                                    <input type="hidden" name="cake" value="<?php echo $_SESSION['formToken']; ?>" class="form-control"/>
                                 </div>
                                <label>New Password</label>
                                    <input type="password" name="passStrutt" id="password" class="form-control" aria-describedby="sizing-addon2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required/>
                                 </div>
                                
                                <button class="btn btn-inverse" type="submit">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="js/dropdown.js"></script>
    <script src="js/flat-ui.js"></script>
    <footer>
    </footer>
</html>
