<!DOCTYPE html>
<html>
    <head>
        <title>Reffle - Create Account</title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/login.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800;900&display=swap" rel="stylesheet">

        <style>
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }

            .inputfile + label {
            font-size: 1.25em;
            display: inline-block;
            width: 100%;
            margin-bottom: 1.875rem;
            text-align: center;
            font-weight: 900;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        </style>
        
        <script>
            document.getElementById("#file").onchange = function(){
                document.getElementById("#imgLabel").textContent = this.files[0].name;
            }

    </script>
    </head>

    <body>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <h1>reffle</h1>
                
                <form action="#" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' />
                        </tr>
                        <tr>
                            <input type='password' name='password' class='form-control' placeholder='password' />
                        </tr>
                        <tr>
                            <input type='email' name='email' class='form-control' placeholder='email' />
                        </tr>
                        <tr>
                        <input type="file" name="file" id="file" class="inputfile" />
                        <label id="imgLabel" for="file" class='btn btn-outline-secondary'>choose a profile image</label>
                        </tr>
                        <tr>
                            <a href="createAccount.php">
                            <input type='submit' value='create account' class='btn btn-primary' /></a>
                        </tr>
                        <br>
                        <tr>
                            <a href='login.php'><input value='return to sign in' class='btn btn-outline-primary' /></a>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>
    
</html>