<?php 
    include 'config/database.php';
    include 'config/core.php';
    session_start();
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

        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR:  User not found :(');

        $query = "SELECT profilePic, userID, username, bio, instagram, twitter, tumblr, linkedin, facebook FROM users u
        WHERE userID = :userID;";

        $stmt=$db->prepare($query);
        $stmt->bindParam(':userID', $id);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $profilePicture=htmlspecialchars($row['profilePic'], ENT_QUOTES);
        $userID=htmlspecialchars($row['userID'], ENT_QUOTES);
        $user=htmlspecialchars($row['username'], ENT_QUOTES);
        $bio=htmlspecialchars($row['bio'], ENT_QUOTES);
        $instagram=htmlspecialchars($row['instagram'], ENT_QUOTES);
        $twitter=htmlspecialchars($row['twitter'], ENT_QUOTES);
        $tumblr=htmlspecialchars($row['tumblr'], ENT_QUOTES);
        $facebook=htmlspecialchars($row['facebook'], ENT_QUOTES);
        $linkedin=htmlspecialchars($row['linkedin'], ENT_QUOTES);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - <?php echo $username?></title>
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
        <link rel="stylesheet" href="css/profile.css" />
        <link rel="stylesheet" href="css/folder.css" />
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <meta http-equiv="Cache-control" content="no-cache">
    </head>
    
    <body>
       <?PHP include 'navigation.php'; ?>
        <div id="container">
            <h3 id="pageTitle"><?php echo $user;?></h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <?PHP 
                    $query = "SELECT i.image, postID FROM posts p
                    JOIN images i
                    ON p.imageID = i.imageID
                    JOIN users u
                    ON p.userID = u.userID
                    WHERE u.userID = :userID
                    ORDER BY p.created DESC";

                    $stmt = $db->prepare($query);
                    $stmt->bindParam(":userID", $id);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);?>
                        
                        <a href="post.php?id=<?PHP echo $postID; ?>"><div class="post" style="background-image:url('uploads/<?PHP echo $image;?>'); background-size:cover; background-position: center;">
                        </div></a>
                    <?PHP } ?>
            </div>
        </div>

    </body>
    
</html>

<?PHP } ?>