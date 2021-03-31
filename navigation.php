<?PHP
  session_start();
  include 'config/core.php';
  include 'config/database.php';

  $username = $_SESSION['userlogin'];

  $query = "SELECT image FROM images i
  JOIN users u
  ON i.imageID = u.imageID
  WHERE userID = username=:username;";
  $stmt=$db->prepare($query);
  $stmt->bindParam(1,$image);
  $stmt->execute( array( ':username' => $username ));

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $image = $row['image'];
  }
?>

<div id="sidenav">
    <a href="index.php" id="home">
        <img src="img/logo.png" alt="reffle logo"/>
        <h1 class='navLink'>reffle</h1>
    </a>
  <a href="#" id="profile">
    <!--change this when database is implemented-->
    <img src="uploads/<?php echo $image; ?>" alt="profile icon" class='image'>
    <h2 class='navLink'>anabnlsn</h2>
  </a>
  <a href="createPost.php" id="createPost" >
    <img src="img/post.png"/>
    <h2 class='navLink'>create post</h2>
  </a>
  <a href="#" id="settings">
    <img src="img/settings.png" alt="settings icon"/>
    <h2 class='navLink'>settings</h2>
  </a>
</div>