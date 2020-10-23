<?php

$invalidPasswordLength = false;
$nonMatchingPasswords = false;
$invalidEmail = false;
$invalidUsername = false;
$duplicate_username = false;

$duplicate_name_error = "* An account with that username already exists";
$username_error = "* Username cannot be empty";
$email_error = "* Invalid email entered";
$passwords_dont_match = "* Your entered passwords don't match";
$password_wrong_length = "* Password must be between 8 and 16 characters";


$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$confirmpassword = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

$email_regexp = "/^([a-zA-Z0-9_\-\.]+)@(([a-zA-Z0-9_\-\.]+)\.)+([a-zA-Z]{2,6})$/";

if($username === "")
{
	$invalidUsername = true;
}

if($password != $confirmpassword)
{
	$nonMatchingPasswords = true;
}

if(!preg_match($email_regexp, $email))
{
	$invalidEmail = true;
}

if(!$password || !isPasswordLengthValid($password))
{
	$invalidPasswordLength = true;
}

//Queries to see if a user with the same account name already exists
$query = "SELECT * FROM users WHERE user_name = :user AND UserId <> :user_id";
$statement = $db->prepare($query);
$statement->bindValue(':user', $username);
$statement->bindValue(':user_id', $id);
$statement->execute();

$result = $statement->fetch();

//If an account with the same name exists, registration will fail
if($result)
{
	$duplicate_username = true;
}

function isPasswordLengthValid($password)
{
	$validPassword = false;

	if(strlen($password) >= 8 && strlen($password) <= 16)
	{
		$validPassword = true;
	}

	return $validPassword;
}