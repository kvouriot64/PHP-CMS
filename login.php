<?php include 'includes/header.php';
	$invalidUser = false;
	$invalidPassword = false;
	
	$usernameError = "Incorrect username or account doesn't exist";
	$passwordError = "Incorrect password";

	if($_POST)
	{
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$query = "SELECT * FROM users WHERE user_name = :user";

		$statement = $db->prepare($query);

		$statement->bindValue(':user', $username);

		$statement->execute();

		$result = $statement->fetch();

		if(!$result && (!password_verify($password, $result['Password']) 
		|| $password != $result['Password']))
		{
			$invalidUser = true;
			$invalidPassword = true;
		}
		elseif($result && (!password_verify($password, $result['Password']) 
		&& $password != $result['Password']))
		{
			$invalidPassword = true;
		}
		elseif($result && (password_verify($password, $result['Password']) 
		|| $password == $result['Password']))
		{
			session_start();
			$_SESSION['UserId'] = $result['UserId'];
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;

			header('Location: index.php');
		}
	}
?>
	<div class="container">
    	<h1>Sign In</h1>
			<form method="post">
			  <fieldset>
			    <div class="form-group">
			      <label for="username">Username:</label>
			      <input name="username" type="text" class="form-control" id="username" placeholder="username">
			    </div>
				<?php if($invalidUser): ?>
			      	<p class="text-danger"><?= $usernameError ?></p>
			      <?php endif ?>


			    <div class="form-group">
			      <label for="password">Password</label>
			      <input name="password" type="password" class="form-control" id="password" placeholder="Password">
			      <?php if($invalidPassword): ?>
			      	<p class="text-danger"><?= $passwordError ?></p>
			      <?php endif ?>
			    </div>

			    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
			  </fieldset>
			</form>

			<p><a href="register.php">Not Registered? Sign Up</a></p>
		</div>
	<?php include 'includes/footer.php' ?>