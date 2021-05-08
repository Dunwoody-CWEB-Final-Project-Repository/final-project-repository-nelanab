<?php
    session_start();
    //Database Configuration File
    include('config/database.php');
    error_reporting(0);

    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - Home</title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" Content-Type: text/html; />
        <meta name="description" content="Reffle - Original Character Art Hosting">
        <meta name="keywords" content="art, character art, art hosting, reffle">
        <meta name="author" content="Ana Nelson">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/login.css" />
        <link rel="icon" href="favicon.ico">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="libs/bootbox.all.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>

    <body>
<?PHP 
    if(isset($_POST['login']))
    {

        // Getting username/ email and password
        $uname=$_POST['username'];
        $password=$_POST['password'];
        // Fetch data from database on the basis of username/email and password
        $sql ="SELECT username,email,password FROM users WHERE (username=:usname || email=:usname)";
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
                $_SESSION['userlogin']=$_POST['username'];
                echo "<script type='text/javascript'> document.location = 'home.php'; </script>";
            } 
            else {
                echo "<script>
                    bootbox.alert('Incorrect password');
                </script>";
            }
        }
        else{
        echo "<script>
            bootbox.confirm({
            message: 'This username does not seem to be registered with us! Would you like to make an account?',
            buttons: {
		        confirm: {
		            label: 'Yes!'
		        },
		        cancel: {
		            label: 'No',
                    className: 'btn-outline-danger btn-sm'
		        }
            },
            callback: function(result){  
                if(result==true){
                    window.location = 'createAccount.php';
                }
            }
            });
            </script>";
        }

    }
?>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <div id="logoTitle">
                    <div id="logo" class="image"></div>
                    <h1>reffle</h1>
                </div>
                
                <form method="post">
                    <table>
                        <tr>
                            <input id='username' type='text' name='username' id='username' class='form-control' placeholder='username' required='' />
                        </tr>
                        <tr>
                            <input type='password' name='password' id='password' class='form-control' placeholder='password' required=''/>
                        </tr>
                        <tr>
                            <button type='submit' class='btn btn-primary' name='login'>log in</button>
                        </tr>
                        <br>
                        <tr>
                            <a href='createAccount.php'><input value='create account' class='btn btn-outline-primary' /></a>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>