<!DOCTYPE html>
<?php
    session_start();
    include_once ('Scripts/profile_authenticate.php');
    include_once('Scripts/expiresession.php');
    include_once "Services/dbconnect.php";
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Oleo+Script" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="css/jasny-bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/flat-ui.css" type="text/css" rel="stylesheet"/>
        <link href="css/modal.css" type="text/css" rel="stylesheet"/>
        <link href="css/profile.css" type="text/css" rel="stylesheet"/>
        <style>
            .jumbotron{
                background: url(<?php
                    $user = $_SESSION['username'];
                    $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$user'";
                    $b = $conn->query($a);
                    $fetch= mysqli_fetch_array($b);
                    echo $fetch['background'];
                ?>)no-repeat center center fixed;
                background-size: cover;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="js/flat-ui.js"></script>
        <title><?php echo $_SESSION['username']?> | Profile</title>
    </head>
    <body>
       <?php include('navbar.php')?>
        <?php    
            $error = $_GET['error'];
            $Serror = $_GET['sizeerror'];
            $Terror = $_GET['typeerror'];
            $empty =$_GET['empty'];
            $success = $_GET['success'];
            $delete = $_GET['delete'];
            
            if(filter_var($error, FILTER_VALIDATE_INT) == true &&isset($error)&& $error = 1){
                echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Post upload was unsuccessful!</p></div>';
            }
            if(filter_var($Serror, FILTER_VALIDATE_INT) == true &&isset($Serror)&& $Serror = 1){
                echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Post was too big. Keep Image under 25MB</p></div>';
            }
            if(filter_var($Terror, FILTER_VALIDATE_INT) == true &&isset($Terror)&& $Terror = 1){
                echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Only jpg, png, and jpeg files are allowed!</p></div>';
            }
            elseif(filter_var($empty, FILTER_VALIDATE_INT) == true &&isset($empty)&& $empty = 1){
                echo '<div class="alert alert-danger alert-dismissable"><p style="text-align: center">Post was empty!</p></div>';
            }
            elseif(filter_var($success, FILTER_VALIDATE_INT) == true &&isset($success)&& $success = 1){
                echo '<div class="alert alert-success alert-dismissable"><p style="text-align: center">Post successful!</p></div>';
            } 
            elseif(filter_var($delete, FILTER_VALIDATE_INT) == true &&isset($delete)&& $delete = 1){
                echo '<div class="alert alert-success alert-dismissable"><p style="text-align: center">Post deleted!</p></div>';
            } 
        ?>
        
       <div class="jumbotron">
            <div id="profile" class="container">
                <div id="wrapper" class="media">
                      <div class="media-left media-top profile-header-img">
                          <img class="media-object img-circle" src="
                            <?php 
                                $user = $_SESSION['username'];
                                $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$user'"; $b = $conn->query($a);
                                $fetch= mysqli_fetch_array($b);
                                
                                echo $fetch['avatar'];
                            ?>" style="width:120px;height:120px;"/>
                      </div>
                      <div class="media-body">
                          <h4 class="media-heading"><?php echo $_SESSION['username']; ?></h4>
                          <p id="description" class="media-body">
                            <?php
                                $user = $_SESSION['username'];
                                $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$user'"; $b = $conn->query($a);
                                $fetch= mysqli_fetch_array($b);
                                
                                echo htmlspecialchars($fetch['description']);
                            ?>
                          </p>
                      </div>
                </div>
            </div>
        </div>
        
        <?php include('Scripts/postModal.php')?>
        <?php
            $user = $_SESSION['username'];
            $postCount = "SELECT * FROM heroku_28db52ced0c34d2.posts WHERE username = '$user'"; $theCount = $conn->query($postCount);
            $finalCount = mysqli_num_rows($theCount);
            if(isset($_GET["page"])){
                $page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT);
            }
            $offset = 16 * $page;
            $a = "SELECT * from heroku_28db52ced0c34d2.`posts` WHERE username = '$user' ORDER BY upload_date desc LIMIT 16 OFFSET ".$offset; $b = $conn->query($a);  
            $i = 0;
            echo '<div id="post_row" class="row">';
            while($fetch= mysqli_fetch_assoc($b)){
                echo '<div id="post_container" class="col-md-3">';
                include('Scripts/contentModal.php');
                ?>
                
                <div id="post" class="thumbnail">
                    <a href="#"><h7 style="padding-left: 10px; font-size:12px;" class="text-muted"><?php echo $fetch['username']; ?></h7></a>
                    <hr style="margin: 0; width: 95%;">
                    <img src="<?php echo $fetch['content_path']; ?>" type="button" data-toggle="modal" data-target="#contentModal<?php echo $i; ?>">
                    <div class="caption">
                        <form action="Services/delete.php" method="post">
                            <div class="form-group">
                                <input type="hidden" name="delBool" value="1" class="form-control"/>
                                <input type="hidden" name="postPath" value="<?php echo $fetch['content_path']; ?>" class="form-control"/>
                            </div>
                            <button class="btn btn-inverse" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            
            </div>
        <?php
        $i++;
        if ($i%4 == 0) echo '</div><div id="post_row" class="row">';
        }?>
        <div class="row">
            <div class="container">       
                <ul class="pagination">
                    <?php 
                        $r = 0;
                        $p = ($r + 1) - 1;
                        $s = round($finalcount/16);
                        if($finalcount%16 < 8){
                            $s++;
                        }
                        while($r != $s){
                           $r++; echo'<li><a href="https://strutt.herokuapp.com/profile.php?page='.$p.'">'.$r.'</a></li>'; } 
                    ?>
                </ul>
            </div>
        </div>
    </body>
    <script src="js/dropdown.js"></script>
    <footer>
    </footer>
</html>
