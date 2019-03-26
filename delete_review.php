<?php
require 'db/connect.php';
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$restId = filter_input(INPUT_GET, 'restid', FILTER_VALIDATE_INT);

if($id && $restId)
{
	$query = "DELETE FROM Reviews WHERE ReviewId = :id";
	
	$statement = $db->prepare($query);

	$statement->bindValue(':id', $id);

	$statement->execute();

	Header('Location: restaurant.php?id=' . $restId);
}
else
{
	Header('Location: index.php');
}
?>