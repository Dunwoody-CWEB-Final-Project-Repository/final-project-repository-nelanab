<?php 
    include 'config/database.php';
    include 'config/core.php';
    session_start();
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userlogin'];
        $search_term = isset($_GET['s']) ? htmlspecialchars(strip_tags($_GET['s'])) : "";

        
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - Search</title>
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
        <link rel="stylesheet" href="css/search.css" />
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    
    <body>
       <?PHP include 'navigation.php'; ?>
        <div id="container">
            <h3 id="pageTitle">Results for: <?php echo $search_term?></h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="&#9906; search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>
            <div id="postContainer">
                <h2>Posts</h2>
                <div id="posts">
                <?PHP 
                    $postQuery = "SELECT i.image, title, f.name, p.postID FROM posts p JOIN folders f ON f.folderID = p.folderID JOIN images i ON i.imageID = p.imageID WHERE title LIKE :title
                    OR description LIKE :description 
                    OR f.name LIKE :name;
                    ORDER BY p.created DESC";

                    $stmt = $db-> prepare($postQuery);

                    $search_term_for_query = "%{$search_term}%";
                    $stmt->bindParam(':name', $search_term_for_query);
                    $stmt->bindParam(':title', $search_term_for_query);
                    $stmt->bindParam(':description', $search_term_for_query);

                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);?>
                        <a href="post.php?id=<?PHP echo $postID; ?>"><div class="post" style="background-image:url('uploads/<?PHP echo $image;?>'); background-size:cover; background-position: center;">
                            </div></a>
                    <?PHP } ?>
                </div>
                <h2>Folders</h2>
                <div id="folders">
                <?PHP
                    $folderQuery = "SELECT i.image, f.name, f.userID, f.folderID FROM folders f JOIN images i ON f.imageID = i.imageID
                    WHERE f.name LIKE :name;";

                    $stmt = $db-> prepare($folderQuery);

                    $search_term_for_query = "%{$search_term}%";
                    $stmt->bindParam(':name', $search_term_for_query);

                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);?>
                        <a href="folderPosts.php?id=<?PHP echo $folderID?>"><div class="folder" style="background-image:url('uploads/<?PHP echo $image?>'); background-size:cover; background-position: center;"><h1><?PHP echo $name?></h1>
                                </div></a>
                    <?PHP } ?>
                </div>
                <h2>Users</h2>
                <div id="users">
                    <?PHP
                        $usersQuery = "SELECT userID, username, profilePic FROM users
                        WHERE username LIKE :username;
                        ORDER BY u.created DESC";

                        $stmt = $db-> prepare($usersQuery);

                        $search_term_for_query = "%{$search_term}%";
                        $stmt->bindParam(':username', $search_term_for_query);

                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row);?>
                            <a href="profile.php?id=<?PHP echo $userID?>"><div class="folder" style="background-image:url('uploads/<?PHP echo $profilePic?>'); background-size:cover; background-position: center;"><h1><?PHP echo $username?></h1>
                            </div></a>
                    <?PHP } ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?PHP } ?>