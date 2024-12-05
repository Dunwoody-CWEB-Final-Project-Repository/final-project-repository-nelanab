<?php
session_start();
include 'config/database.php';
include 'config/core.php';

if(strlen($_SESSION['userlogin']) == 0){
    header('location:index.php');
}
else{
    $username = $_SESSION['userlogin'];
    if($_POST){
        try{
            $check = "SELECT userID FROM posts WHERE postID = ? LIMIT 1";
            $checkSTMT=$db->prepare($check);
            $checkSTMT->bindParam(1, $_POST['object_id']);
            $checkSTMT->execute();
            $checkrow=$checkSTMT->fetch(PDO::FETCH_ASSOC);

            $userIDCheck = htmlspecialchars($checkrow['userID'], ENT_QUOTES);

            $getIDQuery = 'SELECT userID FROM users WHERE username=:username';

            $getID = $db->prepare($getIDQuery);
            $getID->bindParam(":username", $username);
            $getID->execute();

            $usernameRow = $getID->fetch(PDO::FETCH_ASSOC);

            $loggedInID = htmlspecialchars($usernameRow['userID'], ENT_QUOTES);

            if ($loggedInID == $userIDCheck){
        // delete query
                $query="DELETE FROM posts WHERE postID= ? ";
                $stmt=$db->prepare($query);
                $stmt->bindParam(1, $_POST['object_id']);

                if ($stmt->execute())
                {   
                }
                else
                {
                    die("Unable to delete record.");
                }	
            }
            else{
                echo "This post does not belong to you!";
                echo "<br>";
                echo "<a href='home.php'>Return to homepage</a>";
            }
        }
            

        catch(PDOException $exception)
        {
            die("ERROR :".$exception->getMessage());
        }
    }
}
?>