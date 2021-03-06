<?php 
    include 'config/database.php';
    include 'config/core.php';
    session_start();
    
    
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userlogin'];

        $getID = "SELECT userID FROM users WHERE username='$username' LIMIT 0,1";
        foreach ($db->query($getID) as $row) {
            $userID = $row['userID'];
        }

    if ($_POST){
        try{
            

            $query = "SET foreign_key_checks = 0;
                        
                        INSERT INTO posts
                        SET title=:title, folderID=:folderID, description=:description, userID = :userID;
                        
                        SELECT LAST_INSERT_ID() INTO @postID;
                        
                        INSERT INTO images
                            SET image=:image;
    
                        SELECT LAST_INSERT_ID() INTO @imageID;
    
                        UPDATE posts SET imageID = @imageID WHERE postID = @postID;

                        SET foreign_key_checks = 1;
                    ";
    
           // $db->query("BEGIN");
            $stmt = $db->prepare($query);
    
            $title=htmlspecialchars(strip_tags($_POST['title']));
            $folderID=htmlspecialchars(strip_tags($_POST['folderID']));
            $description=htmlspecialchars(strip_tags($_POST['description']));
            $image=!empty($_FILES["image"]["name"])
            ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
            : "";
            $image=htmlspecialchars(strip_tags($image));
    
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":folderID", $folderID);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":image", $image);
            $stmt->bindParam(":userID", $userID);
    
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
                            echo "<scriipt>window.location = 'profile.php?id='" . $userID . "';</script>;";
                        }
                        else{
                            echo"<script>alert('Unable to upload photo.')</script>";
                        }
                    }
                    else{
                        echo "<script>alert('{$file_upload_error_messages}')</script>";
                    }
                }
               echo "<script>window.location('home.php')</script>";
            }
            else{
                echo "<script>alert('Unable to create post. Please try again.')</script>";
            }
            $stmt->closeCursor();
            header('Location: profile.php?id=' . $userID . '');
        }
        catch(PDOException $exception){
            die('Error: ' . $exception->getMessage());
        }
    
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - Create Post</title>
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
        <link rel="icon" href="favicon.ico">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <script src="libs/bootstrap-4.0.0/js/tests/vendor/jquery-1.9.1.min.js"></script>
        <style>
            body{
                font-size: 8.33333vw;
            }
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }

            .inputfile + label {
                font-size: 1.25vw;
                display: inline-block;
                width: 14.84375vw;
                height: 4.375rem;
                margin-bottom: 1.5rem;
                text-align: center;
                font-weight: 900;
                display: flex;
                justify-content: center;
                align-items: center;
            }
        </style>
    </head>
    
    <body>
        <?php
            include 'navigation.php';
        ?>
        <div id="container">
            <h3 id="pageTitle">Create Post</h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data" id="postForm">
                    <div id="imgUpload">
                        <img src="#" id="uploadImg" style='display: none;'/>
                        <input onchange="readURL(this);" type="file" name="image" id="file" class="inputfile" />
                        <label id="imgLabel" for="file" class='btn btn-outline-secondary'>choose image</label>
                    </div>
                    <div id="postInfo">
                        <table>
                            <tr>
                                <input type='text' name='title' class='form-control' placeholder='title' required autocomplete="off"/>
                            </tr>
                            <tr>
                                <select class='form-control' name='folderID'>
                                    <option value="" disabled selected hidden>select folder</option>
                                    <option>
                                        <?php
                                            $getFolders = "SELECT folderID, name FROM folders WHERE userID='$userID' ORDER BY name";
                                            foreach ($db->query($getFolders) as $row) {
                                                extract($row);
                                                echo "<option value='{$folderID}'>{$name}</option>";
                                            }
                                        ?>
                                    </option>
                                </select>
                            </tr>
                            <tr>
                                <textarea name='description' class='form-control' placeholder='description' id="description"></textarea>
                            </tr>
                            <tr>
                            <a href="profile.php?id=<?PHP echo $userID?>">
                                <input type='submit' value='create post' class='btn btn-primary button' /></a>
                                <a href="profile.php?id=<?PHP echo $userID?>">
                                <div class='btn btn-outline-danger button'>discard</div></a>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>

    </body>
    <script>
        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById("uploadImg").style.display="block";
                $('#uploadImg').attr('src', e.target.result);
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