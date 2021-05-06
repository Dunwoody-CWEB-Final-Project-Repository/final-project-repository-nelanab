<?PHP 
  $username = $_SESSION['userlogin'];

  $nav = "SELECT profilePic, userID FROM users WHERE username='$username' LIMIT 0,1";
  foreach ($db->query($nav) as $row) {
    $profilePic = htmlspecialchars($row['profilePic'], ENT_QUOTES);
    $loggedInID = htmlspecialchars($row['userID'], ENT_QUOTES);
  }
?>

<div id="sidenav">
    <a href="home.php" id="home">
        <img src="img/logo.png" alt="reffle logo"/>
        <h1 class='navLink'>reffle</h1>
    </a>
  <a href="profile.php?id=<?PHP echo $loggedInID?>" id="profile">
    <img src="uploads/<?php echo $profilePic; ?>" alt="profile icon" class='image'>
    <h2 class='navLink'><?php echo $username; ?></h2>
  </a>
  <a href="createPost.php" id="createPost" >
    <img src="img/post.png"/>
    <h2 class='navLink'>create post</h2>
  </a>
  <a href="settings.php" id="settings">
    <img src="img/settings.png" alt="settings icon"/>
    <h2 class='navLink'>settings</h2>
  </a>
  <a href="logout.php" id="logout">
    <button class='btn btn-outline-danger button'>logout</button>
  </a>
</div>