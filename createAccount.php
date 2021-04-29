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
        echo "<script>alert('$username already exists. Please pick a different username!')</script>";
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
        <script src="libs/bootstrap-4.0.0/js/tests/vendor/jquery-1.9.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="libs/bootbox.all.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="libs\bootstrap-4.0.0\dist\js\bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">

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

            const logo = document.querySelector('#logo');
            const tooltip = document.querySelector('#tooltip');
            Popper.createPopper(logo, tooltip, {
                placement: 'right',
            });

            function checkUsernameAvail() {
                jQuery.ajax({
                    url: "check_availability.php",
                    data: 'username='+$('#username').val(),
                    type: "POST",
                    success: function(data){
                        console.log("ran");
                    },
                    error: function(){
                        console.log("Check username availabilty not working correctly.")
                    }
                });
            }
        </script>
        
    </head>

    <body>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <div id="logoTitle">
                    <img src="img/logo.png" alt="apple logo" aria-describedby="tooltip" id="logo"/>
                    <div id="tooltip" role="tooltip">
                        Testing
                        <div id="arrow" data-popper-arrow>
                    </div>
                    <h1>reffle</h1>
                </div>
                
                <form action='' method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' onBlur="checkUsernameAvail()" id="username" required />
                            </div>
                            
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