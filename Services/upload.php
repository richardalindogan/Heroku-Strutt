<?php
/** post upload to user profile **/
    use Aws\S3\Exception\S3Exception;
    require('/app/vendor/autoload.php');
    session_start();
    $postUser = $_SESSION['username'];

    include_once "dbconnect.php";
    $postDesc =$conn->real_escape_string(filter_var($_POST['postDescription'], FILTER_SANITIZE_STRING));
    $postCat = $conn->real_escape_string(filter_var($_POST['category'], FILTER_SANITIZE_STRING));
    $pT = $conn->real_escape_string($_POST['tid']);
    
    if(isset($pT) && $pT == $_SESSION['postToken']){
        if($_FILES['uPost']['size'] > 0){
            if($_FILES["uPost"]["size"] > (25*25000000)){
                error_log("User post image size error for:".$postUser." on: ".date("F j, Y, g:i a"));
                header("LOCATION: https://strutt.herokuapp.com/profile.php?sizeerror=1");
                exit;
            }
            else{
                $file = $_FILES['uPost'];

                //file details
                $file_name = $file['name'];
                $tmp_name = $file['tmp_name'];

                $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
                if($file_ex != "jpg" && $file_ex != "png" && $file_ex != "jpeg"){
                    error_log("User post image format error for:".$postUser." on: ".date("F j, Y, g:i a"));
                    header("LOCATION: https://strutt.herokuapp.com/profile.php?typeerror=1");
                    exit;
                }
                else{
                    $extension = explode('.', $file_name);
                    $extension = strtolower(end($extension));

                    // Temp details
                    $temp_key = md5(uniqid());
                    $temp_file_name = "{$temp_key}.{$extension}";


                    //uploading to AWS s3
                    $s3 = Aws\S3\S3Client::factory();
                    $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
                    try{
                        $result = $s3->putObject(array(
                            'Bucket' => $bucket,
                            'Key' => "posts/{$temp_file_name}",
                            'Body' => fopen($file['tmp_name'], 'rb'),
                            'ACL' => 'public-read'
                        ));

                        $postRefPath = $result['ObjectURL'];
                        $a = "INSERT INTO heroku_28db52ced0c34d2.posts (id,username,content_path,category,description,upload_date) VALUES ('$temp_key','$postUser','$postRefPath','$postCat','$postDesc',NOW())";
                        $conn->query($a);
                        error_log("User post upload success for:".$postUser." on: ".date("F j, Y, g:i a"));

                    }catch(S3EXception $e){
                        error_log("User post upload failure for:".$postUser." on: ".date("F j, Y, g:i a"));
                        header("LOCATION: https://strutt.herokuapp.com/profile.php?error=1");
                    }
                }
            }
            error_log("User post upload success for:".$postUser." on: ".date("F j, Y, g:i a"));
            header("LOCATION: https://strutt.herokuapp.com/profile.php?success=1");
        }
        else{
            error_log("User post upload empty form for:".$postUser." on: ".date("F j, Y, g:i a"));
            header("LOCATION: https://strutt.herokuapp.com/profile.php?empty=1");
        }
    }
    else{
        header("LOCATION: https://strutt.herokuapp.com/index.php");
    }
?>