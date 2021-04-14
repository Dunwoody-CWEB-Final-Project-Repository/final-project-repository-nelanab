<?php 
    session_start();
    include 'config/database.php';
    include 'config/core.php';
    
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userlogin'];

        $getID = $db->prepare('SELECT userID FROM users WHERE username=:username',
        array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
        $getID->execute(array(':username' => $username) );
        while ($row = $getID->fetch(PDO::FETCH_ASSOC)){
            $userID = $row['userID'];
        }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - Home</title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/navigation.css" />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <?php
            include 'navigation.php';
        ?>
        <div id="container">
            <h3 id="pageTitle">Home</h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
            <?PHP 
            $query = "SELECT p.title, p.description, i.image, u.username, f.name, u.profilePic FROM posts p
            JOIN images i
            ON p.imageID = i.imageID
            JOIN users u 
            ON p.userID = u.userID
            JOIN folders f
            ON f.folderID = p.folderID
            ORDER BY p.created DESC";

            $stmt = $db->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);?>
                <div class="card d-flex-row flex-wrap">
                    <div class="card-header" style="background-image: url('uploads/<?php echo $image; ?>'); background-size: cover; background-position: center;">
                        <div class='card-image' style="background-image: url('uploads/<?php echo $image; ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                    </div>
                    <div class="card-block d-flex">
                        <h2 class="card-title"><?PHP echo $title; ?></h2>
                        <h3 class="card-character"><?PHP echo $name; ?></h3>
                        <p class="card-text"><?PHP echo $description; ?></p>
                        <div class='card-user align-self-end d-flex align-items-center'>
                            <img src='uploads/<?PHP echo $profilePic; ?>'/>
                            <h3 class="card-username"><?PHP echo $username; ?></h3>
                        </div>
                    </div>
                </div>
                <?PHP
            }
            ?>
                
            </div>
        </div>

    </body>
</html>
<?php }?>