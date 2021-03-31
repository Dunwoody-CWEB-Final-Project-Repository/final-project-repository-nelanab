<?
    session_start();

    include('config.php');
    error_reporting(0);

    if(isset($_POST['login'])){
        $username=$_POST['username'];
        $password=$_POST['password'];

        $sql = "SELECT username, password FROM users WHERE username=:username";

        $query= $db -> prepare($sql);
        $query-> bindParam(':username', $username, PDO::PARAM_STR);
        $query-> execute();
        $results=$query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0){
            foreach ($results as $row){
                $hashpass=$row->password;
            }

            if (password_verify($password, $hashpass)){
                $_SESSION['userlogin']=$_POST['username'];
                echo "<script type= 'text/javascript'> document.location = 'home.php'; </script>";
            }
            else{
                echo "<script>alert('Password was incorrect');</script>";
            }
        }
        else{
            echo "<script>alert('Username was not found.');</script>"; 
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - Log In</title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/login.css" />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800;900&display=swap" rel="stylesheet">
    </head>

    <body>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <h1>reffle</h1>
                
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' required />
                        </tr>
                        <tr>
                            <input type='password' name='password' class='form-control' placeholder='password' />
                        </tr>
                        <tr>
                            <input type='submit' value='log in' class='btn btn-primary' />
                        </tr>
                        <br>
                        <tr>
                            <a href='createAccount.php'><input value='create account' class='btn btn-outline-primary' /></a>
                        </tr>
                    </table>
                </form>
            </div>
            <a href='adminLogin.php' class='align-self-end ml-auto position-absolute pl-2 pb-1'><svg width="50" height="50" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M45 36C45 40.9706 40.9706 45 36 45C31.0294 45 27 40.9706 27 36C27 31.0294 31.0294 27 36 27C40.9706 27 45 31.0294 45 36Z" stroke="#9FCB56" stroke-width="4"/>
                <path d="M31.3304 9L29.9353 17.5468C27.347 18.3891 24.997 19.756 23.0123 21.5212L14.952 18.456L10.2825 26.5439L16.9205 31.9714C16.6449 33.271 16.5 34.6187 16.5 36C16.5 37.3813 16.6449 38.7289 16.9205 40.0285L10.2825 45.456L14.952 53.5439L23.0239 50.4891C25.0062 52.2493 27.352 53.6125 29.9353 54.4532L31.3304 63H40.6696L42.0647 54.4532C44.653 53.6109 47.003 52.244 48.9877 50.4788L57.048 53.544L61.7175 45.4561L55.0795 40.0286C55.3551 38.729 55.5 37.3813 55.5 36C55.5 34.6187 55.3551 33.2711 55.0795 31.9715L61.7175 26.544L57.048 18.4561L48.9761 21.5109C46.9938 19.7507 44.648 18.3875 42.0647 17.5468L40.6696 9H31.3304Z" stroke="#F9F9F9" stroke-width="4"/>
                </svg>
            </a>
        </div>
    </body>
</html>