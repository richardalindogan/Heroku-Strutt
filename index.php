<!DOCTYPE html>
<?php
    session_start();
    include_once('Scripts/user_authenticate.php');
    include_once('Scripts/expiresession.php');
    include_once "dbconnect.php";
?>
<html>
    <head>
        <meta charset="utf-8"/>
        <link href="https://fonts.googleapis.com/css?family=Oleo+Script" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="css/jasny-bootstrap.min.css" type="text/css" rel="stylesheet"/>
        <link href="css/flat-ui.css" type="text/css" rel="stylesheet"/>
        <link href="css/modal.css" type="text/css" rel="stylesheet"/>
        <link href="css/format.css" type="text/css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <title>Strutt | Show your fashion</title>
    </head>
    <body>
        <div id="wrapper">
            <?php include('navbar.php')?>
            <?php    
                $verified = $_GET['verified'];
                if(filter_var($verified, FILTER_VALIDATE_INT) == true &&isset($verified)&& $verified = 1){
                    echo '<div class="alert alert-success alert-dismissable"><p style="text-align: center">User verified!</p></div>';
                }
            ?>
            <div id="content_platform">
                <div class="jumbotron">
                    <h1 class="text-center">Strutt</h1>
                    <p class="text-center">Repository for Design</p>
                </div>
                <div id="content">
                    <?php include('Scripts/postModal.php')?>
                    
                     <?php
                        $postCount = "SELECT * from heroku_28db52ced0c34d2.posts"; $theCount = $conn->query($postCount);
                        $finalCount = mysqli_num_rows($theCount);
                        if(isset($_GET["page"])){
                            $page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT);
                        }
                        $offset = 16 * $page;
                        $a = "SELECT * from heroku_28db52ced0c34d2.posts ORDER BY upload_date desc LIMIT 16 OFFSET ".$offset; $b = $conn->query($a);  
                        $i = 0;
                        echo '<div id="post_row" class="row">';
                        while($fetch= mysqli_fetch_assoc($b)){
                            echo '<div id="post_container" class="col-md-3">';
                            include('Scripts/contentModal.php');
                            ?>
                    
                            <div id="post" class="thumbnail">
                                <a href="profiles.php?user=<?php echo $fetch['username']; ?>"><h7 style="padding-left: 10px; font-size:12px;" class="text-muted"><?php echo $fetch['username']; ?></h7></a>
                                <hr style="margin: 0; width: 95%;">
                                <img src="<?php echo $fetch['content_path']; ?>" type="button" data-toggle="modal" data-target="#contentModal<?php echo $i; ?>">
                                <div class="caption">
                                    <!--<div><div type="button" class="glyphicon glyphicon-heart"></div><p></p></div>-->
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
                                        $r++; echo'<li><a href="https://strutt.herokuapp.com/index.php?page='.$p.'">'.$r.'</a></li>'; }
                                ?>
                            </ul>
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
