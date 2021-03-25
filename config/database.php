<?PHP
// used to connect to the database
$host=    "localhost";
$db_name= "reffle";
$username= "root";
$password="";

try
{
	$db = new PDO("mysql:host={$host};dbname={$db_name}",$username,$password);
}

// catch error
catch(PDOException $exception)
{
	echo"Connection error: ".$exception->getMessage();
}

?>