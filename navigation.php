<?PHP

  include 'config/core.php';
  include 'config/database.php';

  $query = "SELECT image=:image FROM images i
  JOIN users u
  ON i.imageID = u.imageID
  WHERE userID = 5;";

  $stmt=$db->prepare($query);
  $stmt->bindParam(':image', $image);
  $stmt->execute();
?>

<div id="sidenav">
    <a href="index.php" id="home">
        <img src="img/logo.png" alt="reffle logo"/>
        <h1 class='navLink'>reffle</h1>
    </a>
  <a href="#" id="profile">
    <!--change this when database is implemented-->
    <img src="uploads/<?php $image; ?>" alt="profile icon" class='image'>
    <h2 class='navLink'>anabnlsn</h2>
  </a>
  <a href="#" id="createPost" >
    <img src="img/post.png"/>
    <h2 class='navLink'>create post</h2>
  </a>
  <a href="#" id="settings">
    <img src="img/settings.png" alt="settings icon"/>
    <h2 class='navLink'>settings</h2>
  </a>
</div>