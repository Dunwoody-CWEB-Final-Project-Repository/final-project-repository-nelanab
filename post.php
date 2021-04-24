<?PHP
    session_start();
    include 'config/database.php';
    include 'config/core.php';
    
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{

        $username = $_SESSION['userlogin'];

        $getIDQuery = 'SELECT userID FROM users WHERE username=:username';

        $getID = $db->prepare($getIDQuery);
        $getID->bindParam(":username", $username);
        $getID->execute();

        $usernameRow = $getID->fetch(PDO::FETCH_ASSOC);

        $loggedInID = htmlspecialchars($usernameRow['userID'], ENT_QUOTES);

        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR:  Post not found :(');

        $query = "SELECT p.title, p.description, i.image, u.username, f.name, u.profilePic, u.userID FROM posts p
        JOIN images i
        ON p.imageID = i.imageID
        JOIN users u 
        ON p.userID = u.userID
        JOIN folders f
        ON f.folderID = p.folderID
        WHERE p.postID = ?
        LIMIT 0,1";

        $stmt = $db->prepare($query);

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $title = htmlspecialchars($row['title'], ENT_QUOTES);
        $name = htmlspecialchars($row['name'], ENT_QUOTES);
        $description = htmlspecialchars($row['description'], ENT_QUOTES);
        $image = htmlspecialchars($row['image'], ENT_QUOTES);
        $postusername = htmlspecialchars($row['username'], ENT_QUOTES);

        $profilePicture = htmlspecialchars($row['profilePic'], ENT_QUOTES);
        $userID = htmlspecialchars($row['userID'], ENT_QUOTES);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - </title>
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
        <link rel="stylesheet" href="css/post.css"/>
        <link rel="icon" href="favicon.ico">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <?php
            include 'navigation.php';
        ?>
        <div id="container">
            <h3 id="pageTitle"><a href="profile.php?id=<?PHP echo $userID ?>"><?php echo $postusername, "</a> / ", $title?></h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <div id="imgUpload" style="background-image:url('uploads/<?PHP echo $image;?>'); background-size: cover; background-position: center;">
                    <div id="postImage" style="background-image:url('uploads/<?PHP echo $image;?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                </div>

                <div id="postInfo">
                    <h2 class="card-title"><?PHP echo $title; ?></h2>
                    <h3 class="card-character"><?PHP echo $name; ?></h3>
                    <p class="card-text" style="font-weight: normal;"><?PHP echo $description; ?></p>
                    <div class='card-user align-self-end d-flex align-items-center'>
                        <a href="profile.php?id=<?php echo $userID?>"><img id="profilePic" src='uploads/<?PHP echo $profilePicture; ?>'/>
                        <h3 class="card-username"><?PHP echo $postusername; ?></h3></a>
                        <?PHP 
                            if($userID == $loggedInID){
                                echo "<a id='edit' href='edit.php'><img src='img/edit.png'/></a>";
                                echo "<a id='delete' href='delete.php'><img src='img/Delete.png'/></a>";
                            }
                        ?>
                </div>
            </div>
        </div>

    </body>
</html>

<?PHP } ?>