<?php
session_start();
include '../config/database.php';
include '../config/core.php';

if(strlen($_SESSION['admin']) == 0){
    header('location:index.php');
}
else{
    $admin = $_SESSION['admin'];
    if($_POST){
    try{
    // delete query
        $query="DELETE FROM posts WHERE postID= ? ";
        $stmt=$db->prepare($query);
        $stmt->bindParam(1, $_POST['object_id']);

        if ($stmt->execute())
        {   
            echo "Folder was deleted.";
        }
        else
        {
            die("Unable to delete record.");
        }	
    }
    catch(PDOException $exception)
    {
        die("ERROR :".$exception->getMessage());
    }
}
        
}
?>