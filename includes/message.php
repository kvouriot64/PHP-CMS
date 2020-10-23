<?php
session_start();

if($_SESSION)
{
	$message = "Welcome, " . $_SESSION['username'];
}
else
{
	$message = "Not signed in";
}

?>