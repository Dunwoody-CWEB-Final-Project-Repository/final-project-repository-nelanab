<?php
    session_start();
    include '../config/database.php';
    include '../config/core.php';
    
    if(strlen($_SESSION['admin']) == 0){

    }
    else{
        $adminname = $_SESSION['admin'];

        $page_url = "home.php"
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
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link rel="stylesheet" href="../libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <script src="../libs/bootbox.all.min.js" ></script>
        <script src="../libs/bootstrap-4.0.0/js/tests/vendor/jquery-1.9.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="../libs/bootbox.all.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link rel="stylesheet" href="../css/overrides.css" />
        <link rel="stylesheet" href="../css/login.css" />
        <link rel="stylesheet" href="admin.css" />
        <link rel="icon" href="../favicon.ico">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;800;900&display=swap" rel="stylesheet">
    </head>

    <body>
        <div id="container">
            <a href="logout.php" id="logout">
                <button class='btn btn-outline-danger button'>logout</button>
            </a>
            <nav id="navigation">
            <h2 id="usersNav"><a style="color: #9FCB56" href="home.php">Users</a></h2> 
                <h2 id="postsNav" ><a href="posts.php">Posts</a></h2>
                <h2 id="foldersNav"><a href="folders.php">Folders</a></h2>
            </nav>
            
            <table class="table table-hover table-striped table-dark" id="users">
                <thead>
                    <tr>
                        <th scop="col">id</th>
                        <th scope="col">username</th>
                        <th scope="col">bio</th>
                        <th scope="col">profile picture</th>
                        <th scope="col">actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?PHP 
                        $getUsers = "SELECT userID, username, bio, profilePic FROM users ORDER BY username LIMIT :from_record_num, :records_per_page ;";
                        $stmt = $db->prepare($getUsers);
                        $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
		                $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            extract($row);?>
                            <tr>
                                <th scope="row"><?PHP echo $userID ?></th>
                                <td><?PHP echo $username ?></td>
                                <td><?PHP echo $bio ?></td>
                                <td><img src='../uploads/<?PHP echo $profilePic ?>'/></td>
                                <td><a href ="#" class="btn btn-outline-danger" style='cursor: pointer;' onclick='delete_user(<?PHP echo $userID ?>);' class='btn btn-danger'>delete</a></td>
                                
                            </tr>
                        <?PHP } $stmt->closeCursor();?>
                        <tr>
                            <?php 
                                $query = "SELECT COUNT(*) as total_rows FROM users";
                                $stmt = $db->prepare($query);

                                $stmt->execute();

                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total_rows = $row['total_rows'];

                                include 'paging.php'; 

                                $stmt->closeCursor();
                            ?>
                        </tr>
                </tbody>
            </table>
            
        </div>
    </body>
    <script type='text/javascript'>

        function delete_user(id)
                {
                    bootbox.confirm({
                    message: "<h4>Are you sure? This action cannot be undone.</h4>",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-primary btn-sm'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-outline-danger btn-sm'
                        }
                    },
                    callback: function (result) {

                        if(result==true){
                            $.post('deleteUser.php', {
                                object_id: id
                            }, function(data){
                                console.log('ran');
                                location.reload();
                            }).fail(function() {
                                alert('Unable to delete.');
                            });
                        }
                    }
                });

                return false;
                    
                }

    </script>
</html>
<?PHP } ?>