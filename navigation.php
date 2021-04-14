<?PHP 
  $username = $_SESSION['userlogin'];

  $query = $db->prepare("SELECT profilePic FROM users WHERE username=:username");
  $query->execute(array(':username' => $username) );
  while ($row = $query->fetch(PDO::FETCH_ASSOC)){
    $profilePic = $row['profilePic'];
  }
?>

<div id="sidenav">
    <a href="home.php" id="home">
        <img src="img/logo.png" alt="reffle logo"/>
        <h1 class='navLink'>reffle</h1>
    </a>
  <a href="profile.php" id="profile">
    <!--change this when database is implemented-->
    <img src="uploads/<?php echo $profilePic; ?>" alt="profile icon" class='image'>
    <h2 class='navLink'><?php echo $username; ?></h2>
  </a>
  <a href="createPost.php" id="createPost" >
    <img src="img/post.png"/>
    <h2 class='navLink'>create post</h2>
  </a>
  <a href="#" id="settings">
    <img src="img/settings.png" alt="settings icon"/>
    <h2 class='navLink'>settings</h2>
  </a>
  <a href="logout.php" id="logout">
    <button class='btn btn-outline-danger button'>logout</button>
  </a>
</div>