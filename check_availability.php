<?php 
require_once("config/database.php");

if(!empty($_POST["username"])) {
    $uname= $_POST["username"];
    $sql ="SELECT username FROM  users WHERE username=:username";
    $query= $db -> prepare($sql);
    $query-> bindParam(':username', $uname, PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);

    if($query -> rowCount() > 0)
    {
        echo "<span style='color:#dc3545'>This username is already taken!</span>";
    } else{	
        
    }
}

?>
