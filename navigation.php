<?PHP 
  $username = $_SESSION['userlogin'];

  $nav = "SELECT profilePic, userID FROM users WHERE username='$username' LIMIT 0,1";
  foreach ($db->query($nav) as $row) {
    $profilePic = htmlspecialchars($row['profilePic'], ENT_QUOTES);
    $loggedInID = htmlspecialchars($row['userID'], ENT_QUOTES);
  }
?>
<div id="hamburger" class="ham" onclick="openhamburger();"></div>
<div id="sidenav">
    <div id="closeHam" class="ham" onclick="closehamburger();"></div>
    <a href="home.php" id="home">
      <div id="logo" class="image"></div>
      <h1 class='navLink' id="titleH2">reffle</h1>
    </a>
  <a href="profile.php?id=<?PHP echo $loggedInID?>" id="profile" class="nav">
    <img src="uploads/<?php echo $profilePic; ?>" alt="profile icon" class='image'>
    <h2 class='navLink' id="profileH2"><?php echo $username; ?></h2>
  </a>
  <a href="createPost.php" id="createPost" class="nav">
    <div id="create" class="image"></div>
    <h2 class='navLink' id="createPostH2">create post</h2>
  </a>
  <a href="settings.php" id="settings" class="nav">
    <div id="settingsimg" class="image"></div>
    <h2 class='navLink' id="settingsH2">settings</h2>
  </a>
  <a href="logout.php" id="logout" class="nav">
    <button class='btn btn-outline-danger button'>logout</button>
  </a>
</div>

<script>
  const hamburgerIcon = document.getElementById("hamburger");
  const sidenav = document.getElementById("sidenav");
  const settingsLink = document.getElementById("settingsH2");
  const createPostLink = document.getElementById("createPostH2");
  const profileLink = document.getElementById("profile");
  const homeLink = document.getElementById("titleH2");

  navLink = document.getElementsByClassName("navLink");

  logout = document.getElementById("logout");

  settings = document.getElementById("settings");
  createPost = document.getElementById("createPost");
  profile = document.getElementById("profile");
  home = document.getElementById("home");

  function openhamburger(){
    hamburgerIcon.style.display = "none";
    sidenav.style.left = "0";

    settingsLink.opacity = "100%";
    settings.style.left = "2.59vh";

    profile.style.left = "2.59vh";

    createPostLink.opacity = "100%";
    createPost.style.left = "2.59vh";

    homeLink.opacity = "100%";
    home.style.left = "2.59vh";

    logout.style.left = "2.59vh;";
      
    for(var i=0, len=navLink.length; i<len; i++)
    {
        navLink[i].style["opacity"] = "100%";
        navLink[i].style["width"] = "auto";
        navLink[i].style["height"] = "auto";
    }
  }

  function closehamburger(){
    hamburgerIcon.style.display = "flex";
    sidenav.style.left = "-90vw";

    settingsLink.opacity = "0";
    settings.style.left = "-87.41vw";

    profile.style.left = "-87.41vw";

    createPostLink.opacity = "0";
    createPost.style.left = "-87.41vw";

    homeLink.opacity = "0";
    home.style.left = "-87.41vw";

    logout.style.left = "-87.41vw";
      
    for(var i=0, len=navLink.length; i<len; i++)
    {
        navLink[i].style["opacity"] = "0";
        navLink[i].style["width"] = "0";
        navLink[i].style["height"] = "0";
    }
  }
</script>