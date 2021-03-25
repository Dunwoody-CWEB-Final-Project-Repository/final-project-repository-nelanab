<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - Administration</title>
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/login.css" />
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800;900&display=swap" rel="stylesheet">
    </head>

    <body>
        <div id="container">
            <div id="login" class="align-self-center mx-auto d-block">
                <h1>reffle</h1>
                
                <form action="#">
                    <table>
                        <tr>
                            <input type='text' name='username' class='form-control' placeholder='username' />
                        </tr>
                        <tr>
                            <input type='password' name='password' class='form-control' placeholder='password' />
                        </tr>
                        <tr>
                            <input type='submit' value='log in' class='btn btn-primary' />
                        </tr>
                    </table>
                </form>
            </div>
            <a href='login.php' class='align-self-end ml-auto position-absolute pl-3'><p>Back to login</p>
            </a>
        </div>
    </body>
</html>