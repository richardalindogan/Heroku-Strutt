<?php
/** profile service to delete a post **/
    use Aws\S3\Exception\S3Exception;
    require('/app/vendor/autoload.php');
    session_start();
    
    include_once "dbconnect.php";
    
    $delBool = $conn->real_escape_string($_POST['delBool']);
    $postPath = $conn->real_escape_string($_POST['postPath']);
    if($delBool == 1){
        $post_path = explode('/', $postPath);
        $post_path = strtolower(end($post_path));   
        error_log("Post deletion, ID: ".$post_path." on: ".date("F j, Y, g:i a"));
        
        $s3 = Aws\S3\S3Client::factory();      
        $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
        $delete = $s3->deleteObject(array(
            'Bucket' => $bucket,
            'Key' => "posts/{$post_path}"
        ));
        
        $a = "DELETE FROM heroku_28db52ced0c34d2.posts WHERE content_path = '$postPath'";
        $conn->query($a);
        header("LOCATION: https://strutt.herokuapp.com/profile.php?delete=1");
    }
    else{
         header("LOCATION: https://strutt.herokuapp.com/profile.php");
    }
?>