<?php 
    session_start();
    include 'config/database.php';
    include 'config/core.php';
    
    if(strlen($_SESSION['userLogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userLogin'];

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
                <div class="card d-flex-row flex-wrap">
                    <div class="card-header" style="background-image: url('img/flyingcandy.png'); background-size: cover; background-position: center;">
                        <div class='card-image' style="background-image: url('img/flyingcandy.png'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                    </div>
                    <div class="card-block d-flex">
                        <h2 class="card-title">Cotton Candy Sky</h2>
                        <h3 class="card-character">Dreki</h3>
                        <p class="card-text">Description</p>
                        <div class='card-user align-self-end d-flex align-items-center'>
                            <img src='img/profile.png'/>
                            <h3 class="card-username">username</h3>
                        </div>
                    </div>
                </div>

                <div class="card d-flex-row flex-wrap">
                    <div class="card-header" style="background-image: url('img/pierceOne.png'); background-size: cover; background-position: center;">
                        <div class='card-image' style="background-image: url('img/pierceOne.png'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                    </div>
                    <div class="card-block d-flex">
                        <h2 class="card-title">Title</h2>
                        <p class="card-text">Description</p>
                        <div class='card-user align-self-end d-flex align-items-center'>
                            <img src='img/profile.png'/>
                            <h3 class="card-username">username</h3>
                        </div>
                    </div>
                </div>

                <div class="card d-flex-row flex-wrap">
                    <div class="card-header" style="background-image: url('img/nightmare.png'); background-size: cover; background-position: center;">
                        <div class='card-image' style="background-image: url('img/nightmare.png'); background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                    </div>
                    <div class="card-block d-flex">
                        <h2 class="card-title">Title</h2>
                        <p class="card-text">Description</p>
                        <div class='card-user align-self-end d-flex align-items-center'>
                            <img src='img/profile.png'/>
                            <h3 class="card-username">username</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
<?php }?>