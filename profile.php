<?php 
    include 'config/database.php';

    include 'config/core.php';

    $query = "SELECT image FROM images i
    JOIN users u
    ON i.imageID = u.imageID
    WHERE userID = 5;";

    $stmt=$db->prepare($query);
    $stmt->bindParam(1,$image);
    $stmt->execute();

    $row=$stmt->fetch(PDO::FETCH_ASSOC);

    $image=htmlspecialchars($row['image'], ENT_QUOTES);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - Profile</title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/navigation.css" />
        <link rel="stylesheet" href="css/profile.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <?php
            include 'navigation.php';
        ?>
        <div id="container">
            <h3 id="pageTitle">Profile</h3>
            <form role="search" action="search.php" id="searchForm">
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>

            <div id="postContainer">
                <div id="socialContainer">
                    <img src="uploads/<?php echo $image; ?>" id="profilePic" alt="user profile image"/>
                    <div id="socialTitles" >
                        <h2>anabnlsn</h2>
                        <h3>Hobby Artist</h3>
                        <img src="img/twitter.png"/>
                    </div>
                    
                </div>
            </div>
        </div>

    </body>
</html>