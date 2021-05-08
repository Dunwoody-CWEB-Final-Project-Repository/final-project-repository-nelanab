<?php 
    include 'config/database.php';
    include 'config/core.php';
    session_start();
    
    
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userlogin'];

        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR:  Post not found :(');

        $getID = "SELECT userID FROM users WHERE username='$username' LIMIT 0,1";
        foreach ($db->query($getID) as $row) {
            $userID = $row['userID'];
        }

        $curQuery = "SELECT title, name, p.folderID, description, image FROM posts p JOIN images i ON i.imageID = p.imageID JOIN folders f ON p.folderID = f.folderID WHERE p.postID = '$id' LIMIT 1";
        foreach ($db->query($curQuery) as $row){
            $curTitle = htmlspecialchars($row['title'], ENT_QUOTES);
            $curName = htmlspecialchars($row['name'], ENT_QUOTES);
            $curDesc = htmlspecialchars($row['description'], ENT_QUOTES);
            $curImage = htmlspecialchars($row['image'], ENT_QUOTES);
            $curFolderID = htmlspecialchars($row['folderID'], ENT_QUOTES);
        }

    if (isset($_POST['edit'])){
        try{
            $query = "UPDATE posts
                        SET title=:title, folderID=:folderID, description=:description WHERE postID = :postID";
    
           // $db->query("BEGIN");
            $stmt = $db->prepare($query);
    
            $title=htmlspecialchars(strip_tags($_POST['title']));
            $folderID=htmlspecialchars(strip_tags($_POST['folderID']));
            $description=htmlspecialchars(strip_tags($_POST['description']));
    
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":folderID", $folderID);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":postID", $id);
    
            if($stmt->execute()){
                echo "<script>window.location = 'post.php?id={$id}'</script>";
            }
            else{
                echo "<script>alert('Unable to create post. Please try again.')</script>";
            }
            $stmt->closeCursor();
            echo "<script>window.location = 'post.php?id={$id}'</script>";
        }
        catch(PDOException $exception){
            die('Error: ' . $exception->getMessage());
        }
    
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - Edit Post <?PHP echo $curTitle ?></title>
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
        <link rel="stylesheet" href="css/post.css" />
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
            <h3 id="pageTitle">Edit Post</h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id={$id}");?>" method="POST" enctype="multipart/form-data" id="postForm">
                    <div id="imgUpload" style="background-image:url('uploads/<?PHP echo $curImage;?>'); background-size: cover; background-position: center;">
                        <div id="postImage" style="background-image:url('uploads/<?PHP echo $curImage;?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                    </div>
                    <div id="postInfo">
                        <table>
                            <tr>
                                <input type='text' name='title' class='form-control' placeholder='title' required autocomplete="off" value="<?PHP echo $curTitle ?>"/>
                            </tr>
                            <tr>
                                <select class='form-control' name='folderID'>
                                    <option value="<?PHP echo $curFolderID?>" selected="selected" ><?PHP echo $curName ?></option>
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
                                <textarea name='description' class='form-control' placeholder='description' value="<?PHP echo $curDesc ?>" id="description"><?php echo $curDesc ?></textarea>
                            </tr>
                            <tr>
                            <a href="#">
                                <input type='submit' value='edit post' class='btn btn-primary button' name="edit"/></a>
                                <a href="profile.php?id=<?PHP echo $userID?>">
                                    <div class='btn btn-outline-danger button'>discard</div>
                                </a>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>

    </body>
</html>
<?PHP } ?>