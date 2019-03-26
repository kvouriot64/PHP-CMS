<?php 
	require 'db/connect.php';

	$invalidUser = false;
	$invalidPassword = false;
	
	if($_POST)
	{
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "SELECT * FROM users WHERE user_name = :user";

		$statement = $db->prepare($query);

		$statement->bindValue(':user', $username);

		$statement->execute();

		$result = $statement->fetch();

		if(count($result) > 0)
		{
			if(password_verify($password, $result['Password']) 
			|| $password == $result['Password'])
			{
				session_start();
				$_SESSION['UserId'] = $result['UserId'];
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;

				header('Location: index.php');
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
	</head>
  <body>
    <div id="wrapper">
    <h1>Sign In</h1>
			<form method="post">
			  <fieldset>
			    <div class="form-group">
			      <label for="username">Username:</label>
			      <input name="username" type="text" class="form-control" id="username" placeholder="username">

			    </div>

			    <div class="form-group">
			      <label for="password">Password</label>
			      <input name="password" type="password" class="form-control" id="password" placeholder="Password">
			      <?php if($invalidPassword): ?>
			      	<p><?= $passwordError ?></p>
			      <?php endif ?>
			    </div>

			    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
			  </fieldset>
			</form>

			<p><a href="register.php">Not Registered? Sign Up</a></p>
		</div>
	<footer>
	</footer>
  </body>
</html>