<?php

include 'config/core.php';
include 'config/database.php';

if ( isset ($_POST[ 'signup' ])){
    try{
        $username=htmlspecialchars(strip_tags($_POST['username']));
        
        $password=htmlspecialchars(strip_tags($_POST['password']));
        $options = ['cost' => 12 ];
        $hashedpass = password_hash($password, PASSWORD_BCRYPT, $options );

        $email=htmlspecialchars(strip_tags($_POST['email']));
        $image=!empty($_FILES["image"]["name"])
        ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
        : "";
        $image=htmlspecialchars(strip_tags($image));

        // validate username
        $val = "SELECT * FROM users  WHERE username=:username";
        $valQuery = $db->prepare($val);
        $valQuery->bindParam(":username", $username, PDO::PARAM_STR);
        $valQuery->execute();
        $results = $valQuery->fetchAll(PDO::FETCH_OBJ);
        
        if ( $valQuery->rowCount() == 0 ){

        $query = "INSERT INTO users
                    SET username=:username, password=:password, email=:email;
                    
                    SELECT LAST_INSERT_ID() INTO @userID;
                    
                    INSERT INTO images
                        SET image=:image;

                    SELECT LAST_INSERT_ID() INTO @imageID;

                    UPDATE users SET imageID = @imageID WHERE userID = @userID;
                ";

       // $db->query("BEGIN");
        $stmt = $db->prepare($query);

        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image);

        if($stmt->execute()){
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
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/login.css" />
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
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "check_availability.php",
                    data: 'username='+$('#username').val(),
                    type: "POST",
                    success: function(data){
                        $("#username-availability-status").html(data);
                        $("#loaderIcon").hide();
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
                <h1>reffle</h1>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' onBlur="checkUsernameAvail()" required />
                        </tr>
                        <tr>
                            <input type='password' name='password' class='form-control' placeholder='password' required />
                        </tr>
                        <tr>
                            <input type='email' name='email' class='form-control' placeholder='email' required/>
                        </tr>
                        <tr>
                        <input type="file" name="image" id="file" class="inputfile" />
                        <label id="imgLabel" for="file" class='btn btn-outline-secondary'>choose a profile image</label>
                        </tr>
                        <tr>
                            <a href="createAccount.php">
                            <input type='submit' value='create account' class='btn btn-primary' /></a>
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