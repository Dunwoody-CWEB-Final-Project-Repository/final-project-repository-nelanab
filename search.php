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

        $query = "SELECT i.image, f.name, profilePic, username, p.title FROM posts p CROSS JOIN folders f, users u JOIN images i
        ON p.imageID = i.imageID OR i.imageID = f.imageID
        WHERE
        f.name LIKE :name
        OR p.title LIKE :title
        OR username LIKE :username
        OR description LIKE :description;";

        $stmt = $db-> prepare($query);

        $search_term_for_query = "%{$search_term}%";
        $stmt->bindParam(':name', $search_term_for_query);
        $stmt->bindParam(':title', $search_term_for_query);
        $stmt->bindParam(':username', $search_term_for_query);
        $stmt->bindParam(':description', $search_term_for_query);

        $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - <?php echo $username?></title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/navigation.css" />
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <meta http-equiv="Cache-control" content="no-cache">
    </head>
    
    <body>
       <?PHP include 'navigation.php'; ?>
        <div id="container">
            <h3 id="pageTitle">Results for: <?php echo $search_term?></h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>
            <div id="posts">
            <?PHP 
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);?>
                    <div class="post" style="background-image:url('uploads/<?PHP echo $image;?>'); background-size:cover; background-position: center;">
                    </div>
                <?PHP } ?>
            </div>

            <div id="folderLabel">
                <h3>Folders</h3>
                <a href="folder.php" class="btn btn-primary text-center">new folder</a>
            </div>
            <div id="folders">
            <?PHP 
                $query = "SELECT i.image, f.name FROM folders f
                JOIN images i
                ON f.imageID = i.imageID
                JOIN users u
                ON f.userID = u.userID
                WHERE username = :username
                ORDER BY f.name DESC";

                $stmt = $db->prepare($query);
                $stmt->bindParam(":username", $username);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);?>
                    <div class="folder" style="background-image:url('uploads/<?PHP echo $image?>'); background-size:cover; background-position: center;"><h1><?PHP echo $name?></h1>
                        </div>
                <?PHP } ?>
            </div>
        </div>
    </body>
</html>
<?PHP } ?>