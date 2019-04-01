<?php require 'db/connect.php';
/*
* Executes the necessary SQL commands to
* update users
*/

if($_POST)
{
	$validPassword = true;
	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$confirmpassword = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

	if($id)
	{
		$passwordquery = "SELECT Password FROM Users WHERE UserId = :id";

		$passwordStatement = $db->prepare($passwordquery);
		$passwordStatement->bindValue('id', $id);
		$passwordStatement->execute();

		$passwordResult = $passwordStatement->fetch();


		if(empty($password) && empty($confirmpassword))
		{
			$password = $passwordResult['Password'];
		}
		elseif($password != $confirmpassword)
		{
			echo "The passwords entered don't match, please try again.";
			$validPassword = false;
		}
		elseif(!isPasswordLengthValid($password))
		{
			$validPassword = false;
			echo 'Password must be between 8 and 16 characters';
		}

		if($username && $validPassword && $email)
		{
			$password_hash = password_hash($password, PASSWORD_DEFAULT);

			$insert = "UPDATE users SET user_name = :user, 
										Password = :pass,
										Email = :email
									WHERE UserId = :id";

			$statement = $db->prepare($insert);
			$statement->bindValue(':user', $username);
			$statement->bindValue(':pass', $password_hash);
			$statement->bindValue(':email', $email);
			$statement->bindValue(':id', $id);

			$statement->execute();

			Header("Location: manage_users.php");
		}
	}
}


//Tests if the password is a valid length
function isPasswordLengthValid($password) {
		$validPassword = false;

		if(strlen($password) >= 8 && strlen($password) <= 16)
		{
			$validPassword = true;
		}

		return $validPassword;
	}
?>