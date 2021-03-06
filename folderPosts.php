<?php 
    include 'config/database.php';
    include 'config/core.php';
    session_start();
    if(strlen($_SESSION['userlogin']) == 0){
        header('location:index.php');
    }
    else{
        $username = $_SESSION['userlogin'];

        $getIDQuery = 'SELECT userID FROM users WHERE username=:username';

        $getID = $db->prepare($getIDQuery);
        $getID->bindParam(":username", $username);
        $getID->execute();

        $usernameRow = $getID->fetch(PDO::FETCH_ASSOC);

        $loggedInID = htmlspecialchars($usernameRow['userID'], ENT_QUOTES);

        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR:  User not found :(');

        $query = "SELECT i.image, p.postID, f.name, u.username, f.userID FROM folders f 
                    JOIN posts p 
                    ON p.folderID = f.folderID
                    JOIN images i
                    ON p.imageID = i.imageID 
                    JOIN users u
                    ON f.userID = u.userID
                    WHERE f.folderID = :folderID
                    ORDER BY p.created DESC";

        $stmt = $db->prepare($query);
        $stmt->bindParam(":folderID", $id);
        $stmt->execute();

        $row=$stmt->fetch(PDO::FETCH_ASSOC);

        $name=htmlspecialchars($row['name'], ENT_QUOTES);
        $ownerUser=htmlspecialchars($row['username'], ENT_QUOTES);
        $userID=htmlspecialchars($row['userID'], ENT_QUOTES);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Reffle - <?php echo $name?></title>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" Content-Type: text/html; />
        <meta name="description" content="Reffle - Original Character Art Hosting">
        <meta name="keywords" content="art, character art, art hosting, reffle">
        <meta name="author" content="Ana Nelson">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="libs/bootstrap-4.0.0/dist/css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/overrides.css" />
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/navigation.css" />
        <link rel="stylesheet" href="css/profile.css" />
        <link rel="stylesheet" href="css/folder.css" />
        <link rel="icon" href="favicon.ico">
        <script src="libs/bootstrap-4.0.0/js/tests/vendor/jquery-1.9.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="libs/bootbox.all.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    
    <body>
       <?PHP include 'navigation.php'; ?>
        <div id="container">
            <h3 id="pageTitle"><a href="profile.php?id=<?PHP echo $userID ?>"><?php echo $ownerUser, "</a> / ", $name;?></h3>
            
            <form role="search" action="search.php" id="searchForm"><?PHP 
                    $getUserIDFolder = "SELECT userID FROM folders WHERE folderID = :folderID";
                    $getfIdStmt = $db->prepare($getUserIDFolder);
                    $getfIdStmt->bindParam(":folderID", $id);
                    $getfIdStmt->execute();
                    $idRow = $getfIdStmt->fetch(PDO::FETCH_ASSOC);
                    $folderUserID = htmlspecialchars($idRow['userID']);

                    if ($folderUserID == $userID){
                        echo "<a href='#' onclick='delete_folder({$id})' class='btn btn-outline-danger'>delete folder</a>";
                    }

            ?>
                <div class="input-group">
                    <input type="text" class="search form-control" placeholder="search" name="s" id="search-term" required <?php echo isset($search_term) ? "value='$search_term'":""; ?> />
                </div>
            </form>
            
            <div id="postContainer">
                <?PHP 

                    $query = "SELECT i.image, p.postID, f.name, f.folderID, f.userID FROM folders f 
                    JOIN posts p 
                    ON p.folderID = f.folderID
                    JOIN images i
                    ON p.imageID = i.imageID 
                    WHERE f.folderID = :folderID
                    ORDER BY p.created DESC";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(":folderID", $id);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        extract($row);?>
                        
                        <a href="post.php?id=<?PHP echo $postID; ?>"><div class="post" style="background-image:url('uploads/<?PHP echo $image;?>'); background-size:cover; background-position: center;">
                        </div></a>
                    <?PHP } ?>
            </div>
        </div>

    </body>
    <script type='text/javascript'>
        function delete_folder(id)
		{
			bootbox.confirm({
		    message: "<br><h4>Are you sure? Deleting this folder will also delete all the posts that belong to it. This action cannot be undone.</h4>",
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
					$.post('deleteFolder.php', {
						object_id: id
					}, function(data){
                        window.location = 'profile.php?id=<?PHP echo $loggedInID ?>';
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