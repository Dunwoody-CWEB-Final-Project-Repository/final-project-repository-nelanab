<?php 
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASS','');
	define('DB_NAME','reffle');

	try
	{
		$db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
		$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
	}
	catch (PDOException $e)
	{
		exit("Error: " . $e->getMessage());
	}
?>