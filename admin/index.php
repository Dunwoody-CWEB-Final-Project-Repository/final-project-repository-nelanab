<?php
    session_start();
    //Database Configuration File
    include('../config/database.php');
    error_reporting(0);

    if(isset($_POST['login']))
    {
        $uname=$_POST['username'];
        $password=$_POST['password'];
        $sql ="SELECT username, password FROM admin WHERE username=:usname";
        $query= $db -> prepare($sql);
        $query-> bindParam(':usname', $uname, PDO::PARAM_STR);
        $query-> execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0)
        {
        foreach ($results as $row) {
            $hashpass=$row->password;
        }
        //verifying Password
        if (password_verify($password, $hashpass)) {
            $_SESSION['admin']=$_POST['username'];
            header('Location: home.php');
        } 
        else {
            echo "<script>alert('Wrong Password');</script>";
        }
        }
        else{
            echo "<script>alert('User not registered with us');</script>";
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - Administration</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" Content-Type: text/html; />
        <meta name="description" content="Reffle - Original Character Art Hosting">
        <meta name="keywords" content="art, character art, art hosting, reffle">
        <meta name="author" content="Ana Nelson">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../css/overrides.css" />
        <link rel="stylesheet" href="../css/login.css" />
        <link rel="icon" href="favicon.ico">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800;900&display=swap" rel="stylesheet">
    </head>

    <body>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <div id="logoTitle">
                    <img src="../img/logo.png" alt="apple logo"/>
                    <h1>reffle</h1>
                </div>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' />
                        </tr>
                        <tr>
                            <input type='password' name='password' class='form-control' placeholder='password' />
                        </tr>
                        <tr>
                            <input type='submit' value='log in' class='btn btn-primary' name='login' />
                        </tr>
                    </table>
                </form>
            </div>
            <a href='../index.php' class='align-self-end ml-auto position-absolute pl-3'><p>Back to login</p>
            </a>
        </div>
    </body>
</html>