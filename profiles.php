<!DOCTYPE html>
<?php
    session_start();
    include_once('Scripts/expiresession.php');
    include_once "Services/dbconnect.php";
    $profileUser = $conn->real_escape_string(filter_var($_GET['user'], FILTER_SANITIZE_STRING));
    $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$profileUser'"; $b = $conn->query($a);
    $fetch= mysqli_fetch_array($b);
    if($fetch['username'] == NULL){
         header("LOCATION: https://strutt.herokuapp.com/index.php");
    }
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
                    $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$profileUser'";
                    $b = $conn->query($a);
                    $fetch= mysqli_fetch_array($b);
                    echo $fetch['background'];
                    ?>)no-repeat center center fixed;
                background-size: cover;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="js/flat-ui.js"></script>
        <title><?php echo $fetch['username'] ?> | Profile</title>
    </head>
    <body>
       <?php include('navbar.php')?>
        
       <div class="jumbotron">
            <div id="profile" class="container">
                <div id="wrapper" class="media">
                      <div class="media-left media-top profile-header-img">
                          <img class="media-object img-circle" src="
                            <?php 
                                echo $fetch['avatar'];
                            ?>" style="width:120px;height:120px;"/>
                      </div>
                      <div class="media-body">
                          <h4 class="media-heading"><?php echo $profileUser; ?></h4>
                          <p id="description" class="media-body">
                            <?php
                                $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$profileUser'"; $b = $conn->query($a);
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
            $postCount = "SELECT * FROM heroku_28db52ced0c34d2.posts WHERE username = '$profileUser'"; $theCount = $conn->query($postCount);
            $finalCount = mysqli_num_rows($theCount);
            if(isset($_GET["page"])){
                $page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT);
            }
            $offset = 16 * $page;
            $a = "SELECT * from heroku_28db52ced0c34d2.`posts` WHERE username = '$profileUser' ORDER BY upload_date desc LIMIT 16 OFFSET ".$offset; $b = $conn->query($a);  
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
                           $r++; echo'<li><a href="https://strutt.herokuapp.com/profiles.php?user='.$profileUser.'&page='.$p.'">'.$r.'</a></li>'; } 
                    ?>
                </ul>
            </div>
        </div>
    </body>
    <script src="js/dropdown.js"></script>
    <footer>
    </footer>
</html>
