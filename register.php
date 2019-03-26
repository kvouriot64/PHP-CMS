<?php 
include 'includes/header.php';

if($_POST)
{
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$confirmpassword = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$userType = filter_input(INPUT_POST, 'usertype', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

	if($userType == 'Admin')
	{
		if($password != $confirmpassword)
		{
			echo "The passwords entered don't match, please try again.";
		}
		elseif($password != ADMIN_PASS)
		{
			echo 'Password is incorrect';
		}
		elseif(!$password || !isPasswordLengthValid($password))
		{
			echo 'Password must be between 8 and 16 characters';
		}
		elseif($username && $password == $confirmpassword && $password == ADMIN_PASS && $email)
		{
			$insert = "INSERT INTO users (user_name, Password, Email, UserType, Approved)
						VALUES (:user, :pass, :email, :usertype, :approval)";
						
			$password_hash = password_hash($password, PASSWORD_DEFAULT);

			$statement = $db->prepare($insert);
			$statement->bindValue(':user', $username);
			$statement->bindValue(':pass', $password_hash);
			$statement->bindValue(':email', $email);
			$statement->bindValue(':usertype', $userType);
			$statement->bindValue(':approval', 'y');

			$statement->execute();

			Header("Location: index.php");
		}
	}
	elseif($userType == 'User')
	{
		if($password != $confirmpassword)
		{
			echo "The passwords entered don't match, please try again.";
		}
		elseif(!$password || !isPasswordLengthValid($password))
		{
			echo 'Password must be between 8 and 16 characters';
		}
		elseif($username && $password == $confirmpassword  && $email)
		{
			$password_hash = password_hash($password, PASSWORD_DEFAULT);

			$insert = "INSERT INTO users (user_name, Password, Email)
						VALUES (:user, :pass, :email)";

			$statement = $db->prepare($insert);
			$statement->bindValue(':user', $username);
			$statement->bindValue(':pass', $password_hash);
			$statement->bindValue(':email', $email);

			$statement->execute();

			Header("Location: index.php");
		}
	}
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


?>

    <h1>Register</h1>
			<form method="post">
			  <fieldset>
			    <div class="form-group">
			      <label for="username">Username:</label>
			      <input name="username" type="text" class="form-control" id="username" placeholder="username">
			    </div>

			    <div class="form-group">
			      <label for="email">Email:</label>
			      <input name="email" class="form-control" id="email" placeholder="Your Email">
			    </div>

			    <div class="form-group">
			      <label for="email">User Type:</label>
			      <select id="usertype" name="usertype">
			      	<option value="User">User</option>
			      	<option value="Admin">Admin</option>
			      </select>
			    </div>

			    <div class="form-group">
			      <label for="password">Password:</label>
			      <input name="password" type="password" class="form-control" id="password" placeholder="Password">
			    </div>

			    <div class="form-group">
			      <label for="confirm-password">Confirm Password:</label>
			      <input name="confirm-password" type="password" class="form-control" id="confirm-password" placeholder="Password">
			    </div>

			    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
			  </fieldset>
			</form>
<?php include 'includes/footer.php'; ?>