<?php 
require 'db/connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
	$query = "DELETE FROM users WHERE UserID = :id";

	$statement = $db->prepare($query);

	$statement->bindValue(':id', $id);

	$statement->execute();

	Header('Location: manage_users.php');
}
else
{
	Header('Location: index.php');
}

?>