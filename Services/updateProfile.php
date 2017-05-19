<?php
/** Profile updating service for user **/
    use Aws\S3\Exception\S3Exception;
    require('/app/vendor/autoload.php');
    session_start();
    include_once "dbconnect.php";

    //variables are escaped
    $userPass = $conn->real_escape_string(filter_var($_POST['passStrutt'], FILTER_SANITIZE_STRING));
    $oldPass = $conn->real_escape_string(filter_var($_POST['oldPass'], FILTER_SANITIZE_STRING));
    $profDesc = $conn->real_escape_string(filter_var($_POST['profileDescription'], FILTER_SANITIZE_STRING));
    $cake = $conn->real_escape_string($_POST['cake']);
    $userTag = $_SESSION['username'];
    
    //initial check for valid user token
    if(isset($cake) && $cake == $_SESSION['formToken']){
        if(strlen(trim($profDesc))!= 0||$_FILES['uIcon']['size'] > 0||$_FILES['proBack']['size'] > 0){
            //Description
            if(strlen(trim($profDesc))!= 0){
                $a = "UPDATE heroku_28db52ced0c34d2.`strutt-profile` SET description ='$profDesc' WHERE username = '$userTag'";
                $conn->query($a);
            }
            //User icon
            if(($_FILES['uIcon']['size'] > 0)){
                if($_FILES["uIcon"]["size"] > 1000000){
                    error_log("User icon size error for:".$userTag." on: ".date("F j, Y, g:i a"));
                    header("LOCATION: https://strutt.herokuapp.com/edit.php?sizelimit=1");
                    exit;
                 }
                else{
                    $file = $_FILES['uIcon'];

                    //file details
                    $file_name = $file['name'];
                    $tmp_name = $file['tmp_name'];

                    $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
                    if($file_ex != "jpg" && $file_ex != "png" && $file_ex != "jpeg"){
                        error_log("User icon format error for:".$userTag." on: ".date("F j, Y, g:i a"));
                        header("LOCATION: https://strutt.herokuapp.com/edit.php?formaterror=1");
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
                                'Key' => "avatars/{$temp_file_name}",
                                'Body' => fopen($file['tmp_name'], 'rb'),
                                'ACL' => 'public-read'
                            ));

                            //delete previous image
                            $pre_path = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$userTag'";
                            $pre_path = $conn->query($pre_path);
                            $fetch= mysqli_fetch_array($pre_path);

                            if($fetch['background'] != NULL){
                                $avatar_path = explode('/', $fetch['avatar']);
                                $avatar_path = strtolower(end($avatar_path));
                                $delete = $s3->deleteObject(array(
                                    'Bucket' => $bucket,
                                    'Key' => "avatars/{$avatar_path}"
                                ));
                            }
                            $avatarRefPath = $result['ObjectURL'];
                            $a = "UPDATE heroku_28db52ced0c34d2.`strutt-profile` SET avatar ='$avatarRefPath' WHERE username = '$userTag'";
                            $conn->query($a);
                            error_log("User icon upload success for:".$userTag." on: ".date("F j, Y, g:i a"));
                        }catch(S3EXception $e){
                            error_log("User icon upload failure for:".$userTag." on: ".date("F j, Y, g:i a"));
                            header("LOCATION: https://strutt.herokuapp.com/edit.php?error=1");
                        }
                    }
                }

            }

            //Profile background
            if($_FILES['proBack']['size'] > 0){
                if($_FILES["proBack"]["size"] > 25000000){
                    error_log("Profile background size error for:".$userTag." on: ".date("F j, Y, g:i a"));
                    header("LOCATION: https://strutt.herokuapp.com/edit.php?sizelimit=1");
                    exit;
                 }
                else{
                    $file = $_FILES['proBack'];

                    //file details;
                    $file_name = $file['name'];

                    $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
                    if($file_ex != "jpg" && $file_ex != "png" && $file_ex != "jpeg"){
                        error_log("Profile background format error for:".$userTag." on: ".date("F j, Y, g:i a"));
                        header("LOCATION: https://strutt.herokuapp.com/edit.php?formaterror=1");
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
                                'Key' => "wallpapers/{$temp_file_name}",
                                'Body' => fopen($file['tmp_name'], 'rb'),
                                'ACL' => 'public-read'
                            ));

                            //delete previous image
                            $prebg_path = "SELECT * from heroku_28db52ced0c34d2.`strutt-profile` WHERE username = '$userTag'";
                            $prebg_path = $conn->query($prebg_path);
                            $fetch= mysqli_fetch_array($prebg_path);

                            if($fetch['background'] != NULL){
                                $bg_path = explode('/', $fetch['background']);
                                $bg_path = strtolower(end($bg_path));
                                $delete = $s3->deleteObject(array(
                                    'Bucket' => $bucket,
                                    'Key' => "wallpapers/{$bg_path}"
                                ));
                            }

                            $backgroundRefPath = $result['ObjectURL'];
                            $a = "UPDATE heroku_28db52ced0c34d2.`strutt-profile` SET background ='$backgroundRefPath' WHERE username = '$userTag'";
                            $conn->query($a);
                            error_log("Profile background upload success for:".$userTag." on: ".date("F j, Y, g:i a"));
                        }catch(S3EXception $e){
                            error_log("Profile background upload failure for:".$userTag." on: ".date("F j, Y, g:i a"));
                            header("LOCATION: https://strutt.herokuapp.com/edit.php?error=1");
                        }
                    }
                }
            }
            error_log("Profile update successful for:".$userTag." on: ".date("F j, Y, g:i a"));
            header("LOCATION: https://strutt.herokuapp.com/edit.php?success=1");
        }
        elseif(strlen(trim($userPass)) != 0){

            //User password
            if(strlen(trim($userPass)) != 0 && strlen(trim($oldPass))!= 0 ){
                $a = "SELECT * from heroku_28db52ced0c34d2.`strutt-users` WHERE username = '$userTag'";
                $b = $conn->query($a);
                $fetch= mysqli_fetch_array($b);
                if(password_verify($oldPass,$fetch["password"])){
                    $passUp = password_hash($userPass, PASSWORD_DEFAULT);
                    $a = "UPDATE heroku_28db52ced0c34d2.`strutt-users` SET password ='$passUp' WHERE username = '$userTag'";
                    $conn->query($a); 
                    error_log("User password manual reset success for:".$userTag." on: ".date("F j, Y, g:i a"));
                    header("LOCATION: https://strutt.herokuapp.com/edit.php?success=1");
                    exit;
                }
                else{
                    error_log("User password manual reset failure for:".$userTag." on: ".date("F j, Y, g:i a"));
                    header("LOCATION: https://strutt.herokuapp.com/edit.php?verifyerror=1");
                    exit;
                }
            }
        }
        else{
            error_log("User profile edit form failure for:".$userTag." on: ".date("F j, Y, g:i a"));
            header("LOCATION: https://strutt.herokuapp.com/edit.php?empty=1");
        }
    }
    else{
         header("LOCATION: https://strutt.herokuapp.com/edit.php");
    }
?>