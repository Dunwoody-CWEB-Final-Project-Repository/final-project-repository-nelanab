<?PHP
    session_start();
    include 'config/database.php';
    include 'config/core.php';
    
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{

        $username = $_SESSION['userlogin'];

        $getIDQuery = 'SELECT userID, email, twitter, facebook, linkedin, tumblr, instagram, profilePic FROM users WHERE username=:username';

        $getID = $db->prepare($getIDQuery);
        $getID->bindParam(":username", $username);
        $getID->execute();

        $usernameRow = $getID->fetch(PDO::FETCH_ASSOC);

        $loggedInID = htmlspecialchars($usernameRow['userID'], ENT_QUOTES);
        $loggedInEmail = htmlspecialchars($usernameRow['email'], ENT_QUOTES);
        $loggedInTwitter = htmlspecialchars($usernameRow['twitter'], ENT_QUOTES);
        $loggedInFacebook = htmlspecialchars($usernameRow['facebook'], ENT_QUOTES);
        $loggedInTumblr = htmlspecialchars($usernameRow['tumblr'], ENT_QUOTES);
        $loggedInInstagram = htmlspecialchars($usernameRow['instagram'], ENT_QUOTES);
        $loggedInLinkedIn = htmlspecialchars($usernameRow['linkedin'], ENT_QUOTES);
        $profilePic = htmlspecialchars($usernameRow['profilePic'], ENT_QUOTES);

        if ( isset ($_POST[ 'save' ])){
            try{
                $newusername=htmlspecialchars(strip_tags($_POST['username']));
                $email=htmlspecialchars(strip_tags($_POST['email']));

                if ($_FILES['image']['size'] == 0){
                    $image = $profilePic;
                }
                else{
                    $image=!empty($_FILES["image"]["name"])
                    ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                    : "";
                }
                $twitter=htmlspecialchars(strip_tags($_POST['twitter']));
                $instagram=htmlspecialchars(strip_tags($_POST['instagram']));
                $linkedin=htmlspecialchars(strip_tags($_POST['linkedin']));
                $tumblr=htmlspecialchars(strip_tags($_POST['tumblr']));
                $facebook=htmlspecialchars(strip_tags($_POST['facebook']));
                $password=htmlspecialchars(strip_tags($_POST['password']));
                $options = ['cost' => 12 ];
                $hashedpass = password_hash($password, PASSWORD_BCRYPT, $options );

        
                // validate username
                $val = "SELECT * FROM users WHERE username=:username";
                $valQuery = $db->prepare($val);
                $valQuery->bindParam(":username", $newusername, PDO::PARAM_STR);
                $valQuery->execute();
                $results = $valQuery->fetchAll(PDO::FETCH_OBJ);
                
                if ( $valQuery->rowCount() == 0 OR $username == $newusername){
                    
                    if($password != ""){
                        $sql = "UPDATE users
                                SET username=:username, password=:password, email=:email, twitter=:twitter, facebook=:facebook, linkedin=:linkedin, instagram=:instagram, tumblr=:tumblr, profilePic=:image
                                WHERE userID = :userID";
            
                        $query = $db->prepare($sql);
            
                        $query->bindParam(":username", $newusername, PDO::PARAM_STR);
                        $query->bindParam(":password", $hashedpass, PDO::PARAM_STR);
                        $query->bindParam(":email", $email, PDO::PARAM_STR);
                        if ($twitter != ""){$query->bindParam(":twitter", $twitter, PDO::PARAM_STR);}else{$query->bindValue(":twitter", NULL, PDO::PARAM_STR);}
                        if ($tumblr != ""){$query->bindParam(":tumblr", $tumblr, PDO::PARAM_STR);}else{$query->bindValue(":tumblr", NULL, PDO::PARAM_STR);}
                        if ($instagram != ""){$query->bindParam(":instagram", $instagram, PDO::PARAM_STR);}else{$query->bindValue(":instagram", NULL, PDO::PARAM_STR);}
                        if($facebook != ""){$query->bindParam(":facebook", $facebook, PDO::PARAM_STR);}else{$query->bindValue(":facebook", NULL, PDO::PARAM_STR);}
                        if($linkedin != ""){$query->bindParam(":linkedin", $linkedin, PDO::PARAM_STR);}else{$query->bindValue(":linkedin", NULL, PDO::PARAM_STR);}
                        $query->bindParam(":image", $image, PDO::PARAM_STR);
                        $query->bindParam(":userID", $loggedInID, PDO::PARAM_STR);
            
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
                            echo "<script type='text/javascript'> document.location = 'logout.php'; </script>";
                        }
                        else{
                        echo "<script>alert('Unable to update account. Please try again.')</script>";
                        }
                    }
                    else{
                        $sql = "UPDATE users
                                SET username=:username, email=:email, twitter=:twitter, facebook=:facebook, linkedin=:linkedin, instagram=:instagram, tumblr=:tumblr, profilePic=:image
                                WHERE userID = :userID";
            
                        $query = $db->prepare($sql);
            
                        $query->bindParam(":username", $newusername, PDO::PARAM_STR);
                        $query->bindParam(":email", $email, PDO::PARAM_STR);
                        if ($twitter != ""){$query->bindParam(":twitter", $twitter, PDO::PARAM_STR);}else{$query->bindValue(":twitter", NULL, PDO::PARAM_STR);}
                        if ($tumblr != ""){$query->bindParam(":tumblr", $tumblr, PDO::PARAM_STR);}else{$query->bindValue(":tumblr", NULL, PDO::PARAM_STR);}
                        if ($instagram != ""){$query->bindParam(":instagram", $instagram, PDO::PARAM_STR);}else{$query->bindValue(":instagram", NULL, PDO::PARAM_STR);}
                        if($facebook != ""){$query->bindParam(":facebook", $facebook, PDO::PARAM_STR);}else{$query->bindValue(":facebook", NULL, PDO::PARAM_STR);}
                        if($linkedin != ""){$query->bindParam(":linkedin", $linkedin, PDO::PARAM_STR);}else{$query->bindValue(":linkedin", NULL, PDO::PARAM_STR);}
                        $query->bindParam(":image", $image, PDO::PARAM_STR);
                        $query->bindParam(":userID", $loggedInID, PDO::PARAM_STR);
            
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
                            $_SESSION['userlogin']=$_POST['username'];
                            echo "<script type='text/javascript'> document.location = 'settings.php'; </script>";
                        }
                        else{
                        echo "<script>alert('Unable to update account. Please try again.')</script>";
                        }
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
        <title>Reffle - Settings</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" Content-Type: text/html; />
        <meta name="description" content="Reffle - Original Character Art Hosting">
        <meta name="keywords" content="art, character art, art hosting, reffle">
        <meta name="author" content="Ana Nelson">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/navigation.css" />
        <link rel="stylesheet" href="css/create.css"/>
        <link rel="stylesheet" href="css/settings.css" />
        <link rel="icon" href="favicon.ico">
        <script src="libs/bootstrap-4.0.0/js/tests/vendor/jquery-1.9.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="libs/bootbox.all.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="libs\bootstrap-4.0.0\dist\js\bootstrap.bundle.min.js"></script>
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
            display: inline-block;
            width: 14.84375vw;
            font-size: 1.25vw;
            margin-bottom: 1.875rem;
            text-align: center;
            font-weight: 900;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #9FCB56;
        }

        </style>
    </head>
    
    <body>
        <?php
            include 'navigation.php';
        ?>
        <div id="container">
            <h3 id="pageTitle">Settings</h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
                    <div id="userSettings">
                        <h3>Change username:</h3>
                        <input type="text" name="username" placeholder="username" value="<?php echo $username ?>" class="form-control" required autocomplete="off"/>

                        <h3>Change password:</h3>
                        <input type="password" name="password" placeholder="password" class="form-control" value="" />

                        <h3>Change email:</h3>
                        <input type="text" name="email" placeholder="<?php echo $loggedInEmail ?>" class="form-control" value="<?php echo $loggedInEmail ? : NULL; ?>"/>

                        <img id="profilePic" src="uploads/<?php echo $profilePic;?>"/>

                        <div id="imageInput">
                            <input type="file" name="image" id="image" class="inputfile" onchange="readURL(this);" />
                            <label id="imgLabel" for="image" class='btn btn-outline-secondary'>change picture</label>
                        </div>
                    </div>

                    <div id="socials">
                        <span class="social">
                            <img src="img/twitter.png" style="filter: drop-shadow(0px 6px 10px rgba(29, 161, 242, 0.2));"/>
                            <input type="text" class="form-control" name="twitter" placeholder="<?php echo $loggedInTwitter ? : 'twitter'; ?>" value="<?php echo $loggedInTwitter ? : NULL; ?>"/>
                        </span>

                        <span class="social">
                            <img src="img/instagram.png" style='filter: drop-shadow(0px 6px 10px rgba(240, 0, 115, 0.2));'/>
                            <input type="text" class="form-control" name="instagram" placeholder="<?php echo $loggedInInstagram ? : 'Instagram'; ?>" value="<?php echo $loggedInInstagram ? : NULL; ?>"/>
                        </span>

                        <span class="social">
                            <img src="img/tumblr.png" style='filter: drop-shadow(0px 6px 10px rgba(0, 25, 53, 0.4));'/>
                            <input type="text" class="form-control" name="tumblr" placeholder="<?php echo $loggedInTumblr ? : 'tumblr'; ?>" value="<?php echo $loggedInTumblr ? : NULL; ?>"/>
                        </span>

                        <span class="social">
                            <img src="img/facebook.png" style='filter: drop-shadow(0px 6px 10px rgba(40, 103, 178, 0.2));'/>
                            <input type="text" class="form-control" name="facebook" placeholder="<?php echo $loggedInFacebook ? : 'facebook'; ?>" value="<?php echo $loggedInFacebook ? : NULL; ?>"/>
                        </span>

                        <span class="social">
                            <img src="img/linkedin.png" style='filter: drop-shadow(0px 6px 10px rgba(24, 119, 242, 0.4));'/>
                            <input type="text" class="form-control" name="linkedin" placeholder="<?php echo $loggedInLinkedIn ? : 'linkedin'; ?>" value="<?php echo $loggedInLinkedIn ? : NULL; ?>"/>
                        </span>
                        
                        <span id="buttons">
                            <input type='submit' value='save changes' class='btn btn-primary button' name="save"/>

                            <div class='btn btn-outline-danger button' onclick="location.reload();">discard changes</div>
                        </span>
                    </div>

                    

                </form>
            </div>
        </div>

    </body>
    <script type='text/javascript'>

        console.log('<?PHP echo $profilePic; ?>');
        
        function delete_post(id)
		{
			var answer = confirm("Are you sure?");
			if(answer)
			   {
			   	// if user clicked ok, 
				// pass the id to delete.php and execute the delete query 
				   
				   window.location = "delete.php?id="+id;
			   }
			
		}

        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#profilePic').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
        }

        $("#file").change(function() {
            readURL(this);
        });

    </script>
</html>

<?PHP } ?>