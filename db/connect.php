<?php
/*
* Provides a connection to the serverside MySQL database so 
* PHP-SQL interactions can take place
*/
	define('DB_CONN', "mysql:host=localhost;dbname=serverside;charset=utf8");

	define('DB_USER', 'serveruser');

	define('DB_PASS', 'gorgonzola7!');


	try
	{
		$db = new PDO(DB_CONN, DB_USER, DB_PASS);
	}
	catch (PDOException $e)
	{
		echo "SQL error: " .  $e->getMessage();
		die();
	}
?>