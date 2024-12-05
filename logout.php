<?PHP
    session_start();
    unset($_SESSION['userlogin']);
    session_destroy();
    header("location:index.php");
?>