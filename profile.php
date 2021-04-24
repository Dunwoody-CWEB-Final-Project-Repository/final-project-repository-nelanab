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
                <div id="socialContainer">
                    <img src="uploads/<?php echo $profilePicture; ?>" id="profilePic" alt="user profile image"/>
                    <div id="socialTitles" >
                        <h2><?php echo $user;?></h2>
                        <h3><?PHP echo $bio;?></h3>
                        <?PHP if($twitter == NULL){}
                        else{
                            echo "<a href='{$twitter}' target='_blank' style='filter: drop-shadow(0px 6px 10px rgba(29, 161, 242, 0.2));'><img src='img/twitter.png' alt='twitter logo'/></a>";
                         } ?>
                        <?PHP if($instagram == NULL){}
                        else{
                            echo "<a href='{$instagram}' target='_blank' style='filter: drop-shadow(0px 6px 10px rgba(240, 0, 115, 0.2));'><img src='img/instagram.png' alt='instagram logo'/></a>";
                         } ?>
                        <?PHP if($tumblr == NULL){}
                        else{
                            echo "<a href='{$tumblr}' target='_blank' style='filter: drop-shadow(0px 6px 10px rgba(0, 25, 53, 0.4));'><img src='img/tumblr.png' alt='tumblr logo'/></a>";
                         } ?>
                        <?PHP if($linkedin == NULL){}
                        else{
                            echo "<a href='{$linkedin}' target='_blank' style='filter: drop-shadow(0px 6px 10px rgba(40, 103, 178, 0.2));'><img src='img/linkedin.png' alt='linkedin logo'/></a>";
                         } ?>
                        <?PHP if($facebook == NULL){}
                        else{
                            echo "<a href='{$facebook}' target='_blank' filter: drop-shadow(0px 6px 10px rgba(24, 119, 242, 0.4));'><img src='img/facebook.png' alt='facebook logo'/></a>";
                         } ?>
                    </div>
                    
                </div>
                <div id="postsLabel">
                    <h3>Posts</h3>
                    <?PHP if($userID == $loggedInID){?>
                    <a href="createPost.php" class="btn btn-primary">new post</a>
                    <?PHP } ?>
                </div>
                <div id="posts">
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

                <div id="folderLabel">
                    <h3>Folders</h3>
                    <?PHP if($userID == $loggedInID){?>
                    <a href="folder.php" class="btn btn-primary text-center">new folder</a>
                    <?PHP } ?>
                </div>
                <div id="folders">
                <?PHP 
                    $query = "SELECT i.image, f.name FROM folders f
                    JOIN images i
                    ON f.imageID = i.imageID
                    JOIN users u
                    ON f.userID = u.userID
                    WHERE u.userID = :userID
                    ORDER BY f.name DESC";

                    $stmt = $db->prepare($query);
                    $stmt->bindParam(":userID", $id);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);?>
                        <div class="folder" style="background-image:url('uploads/<?PHP echo $image?>'); background-size:cover; background-position: center;"><h1><?PHP echo $name?></h1>
                            </div>
                    <?PHP } ?>
                </div>
            </div>
        </div>

    </body>
    
</html>

<?PHP } ?>