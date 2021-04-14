<?php 
    include 'config/database.php';
    include 'config/core.php';
    session_start();
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userlogin'];

    $query = "SELECT profilePic FROM users u
    WHERE username = :username;";

    $stmt=$db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);

    $profilePic=htmlspecialchars($row['profilePic'], ENT_QUOTES);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - Profile</title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/navigation.css" />
        <link rel="stylesheet" href="css/profile.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <meta http-equiv="Cache-control" content="no-cache">
    </head>
    
    <body>
       <?PHP include 'navigation.php'; ?>
        <div id="container">
            <h3 id="pageTitle"><?php echo $username?></h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <div id="socialContainer">
                    <img src="uploads/<?php echo $profilePic; ?>" id="profilePic" alt="user profile image"/>
                    <div id="socialTitles" >
                        <h2><?php echo $username;?></h2>
                        <h3>Hobby Artist</h3>
                        <img src="img/twitter.png" alt="twitter logo"/>
                        <img src="img/instagram.png"/>
                        <img src="img/tumblr.png"/>
                        <img src="img/linkedin.png" />
                    </div>
                    
                </div>
                <div id="postsLabel">
                    <h2>Posts</h2>
                    <a href="createPost.php" class="btn btn-primary">new post</a>
                </div>
                <div id="posts">
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="post" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    
                    </div>
                </div>

                <div id="folderLabel">
                    <h2>Folders</h2>
                    <a href="folder.php" class="btn btn-primary text-center">new folder</a>
                </div>
                <div id="folders">
                <div class="folder" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                    </div>
                    <div class="folder" style="background-image:url('img/nightmare.png'); background-size:cover; background-position: center;">
                </div>
            </div>
        </div>

    </body>
</html>

<?PHP } ?>