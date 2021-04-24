<?php

include 'config/core.php';
include 'config/database.php';

if ( isset ($_POST[ 'signup' ])){
    try{
        $username=htmlspecialchars(strip_tags($_POST['username']));
        $email=htmlspecialchars(strip_tags($_POST['email']));
        $image=!empty($_FILES["image"]["name"])
        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
        : "";
        $password=htmlspecialchars(strip_tags($_POST['password']));
        $options = ['cost' => 12 ];
        $hashedpass = password_hash($password, PASSWORD_BCRYPT, $options );

        // validate username
        $val = "SELECT * FROM users  WHERE username=:username";
        $valQuery = $db->prepare($val);
        $valQuery->bindParam(":username", $username, PDO::PARAM_STR);
        $valQuery->execute();
        $results = $valQuery->fetchAll(PDO::FETCH_OBJ);
        
        if ( $valQuery->rowCount() == 0 ){

            $sql = "INSERT INTO users(username, password, email, profilePic) VALUES (:username, :password, :email, :image);";

            $query = $db->prepare($sql);

            $query->bindParam(":username", $username, PDO::PARAM_STR);
            $query->bindParam(":password", $hashedpass, PDO::PARAM_STR);
            $query->bindParam(":email", $email, PDO::PARAM_STR);
            $query->bindParam(":image", $image, PDO::PARAM_STR);

            if($query->execute()){
                if(!empty($_FILES["image"]["tmp_name"])){
                    $target_directory = "uploads/";
                    $target_file = $target_directory . $image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    $file_upload_error_messages="";

                    $check = getimagesize($_FILES["image"]["tmp_name"]);

                    if($check !== false){

                    }
                    else{
                        $file_upload_error_messages.="Please choose an image file.";
                    }

                    $allowed_file_types=array("jpg", "jpeg", "png", "gif");
                    if(!in_array($file_type, $allowed_file_types)){
                        $file_upload_error_messages.="Please choose a jpg, jpeg, png, or gif file.";
                    }

                    if(file_exists($target_file)){
                        $file_upload_error_messages="This image already exists. Please change the file's name.";
                    }

                    if($_FILES['image']['size'] > (5242880)){
                        $file_upload_error_messages.="Please select an image that is less than 5 MB.";
                    }

                    if(empty($file_upload_error_messages)){
                        if(move_uploaded_file($_FILES["image"]["tmp_name"],$target_file)){

                        }
                        else{
                            echo"<script>alert('Unable to upload photo.')</script>";
                        }
                    }
                    else{
                        echo "<script>alert('{$file_upload_error_messages}')</script>";
                    }
                }
                
            }
            else{
            echo "<script>alert('Unable to create account. Please try again.')</script>";
            }
    }
    else{
        echo "<script>alert('Username already exists.')</script>";
    }
    
    }
    catch(PDOException $exception){
        die('Error: ' . $exception->getMessage());
    }

}


?>
<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - Create Account</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" Content-Type: text/html; />
        <meta name="description" content="Reffle - Original Character Art Hosting">
        <meta name="keywords" content="art, character art, art hosting, reffle">
        <meta name="author" content="Ana Nelson">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/login.css" />
        <link rel="icon" href="favicon.ico">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800;900&display=swap" rel="stylesheet">

        <style>
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }

            .inputfile + label {
            font-size: 1.25em;
            display: inline-block;
            width: 100%;
            margin-bottom: 1.875rem;
            text-align: center;
            font-weight: 900;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        </style>
        
        <script>
            function checkUsernameAvail() {
                jQuery.ajax({
                    url: "check_availability.php",
                    data: 'username='+$('#username').val(),
                    type: "POST",
                    success: function(data){
                        console.log("check ran");
                    },
                    error: function(){

                    }
                });
            }


    </script>
    </head>

    <body>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <div id="logoTitle">
                    <img src="img/logo.png" alt="apple logo"/>
                    <h1>reffle</h1>
                </div>
                
                <form action='' method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' onBlur="checkUsernameAvail()" id="username" required />
                        </tr>
                        <tr>
                            <input type='password' name='password' class='form-control' id="password" placeholder='password' required />
                        </tr>
                        <tr>
                            <input type='email' name='email' class='form-control' placeholder='email' required/>
                        </tr>
                        <tr>
                        <input type="file" name="image" id="image" class="inputfile" />
                        <label id="imgLabel" for="image" class='btn btn-outline-secondary'>choose a profile image</label>
                        </tr>
                        <tr>
                            <a href="createAccount.php">
                            <input type='submit' value='create account' class='btn btn-primary' name='signup'/></a>
                        </tr>
                        <br>
                        <tr>
                            <a href='index.php'><input value='return to sign in' class='btn btn-outline-primary' /></a>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
    
</html>