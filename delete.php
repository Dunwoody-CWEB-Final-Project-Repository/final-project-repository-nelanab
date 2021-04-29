<?php
session_start();
include 'config/database.php';
include 'config/core.php';

if(strlen($_SESSION['userlogin']) == 0){
    header('location:index.php');
}
else{
    $username = $_SESSION['userlogin'];

    $getID = $db->prepare('SELECT userID FROM users WHERE username=:username',
    array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false));
    $getID->execute(array(':username' => $username) );

    while ($row = $getID->fetch(PDO::FETCH_ASSOC)){
        $userID = $row['userID'];
    }

    $id=isset($_GET["id"]) ? $_GET["id"]:die("ERROR: Post not found.");

    try{
        
        

        $check = "SELECT userID FROM posts WHERE postID = ? LIMIT 1";
        $checkSTMT=$db->prepare($check);
        $checkSTMT->bindParam(1, $id);
        $checkSTMT->execute();
        $checkrow=$checkSTMT->fetch(PDO::FETCH_ASSOC);

        $userIDCheck = htmlspecialchars($checkrow['userID'], ENT_QUOTES);

        if ($userID == $userIDCheck){
    // delete query
            $query="DELETE FROM posts WHERE postID= ? ";
            $stmt=$db->prepare($query);
            $stmt->bindParam(1,$id);

            if ($stmt->execute())
            {   
                header("Location: home.php");
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
?>