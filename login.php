<?php 
	$invalidUser = false;
	$invalidPassword = false;
	if($_POST)
	{
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



		$query = "SELECT * FROM users";
	}

	include 'includes/header.php';
?>

  
    <h1>Sign In</h1>
			<form method="post">
			  <fieldset>
			    <div class="form-group">
			      <label for="username">Username:</label>
			      <input type="text" class="form-control" id="username" placeholder="username">
			      <small id="emailHelp" class="form-text text-muted">We'll never share your information with anyone else.</small>

			      <?php if($invalidUser): ?>
			      	<p><?= $usernameError ?></p>
			      <?php endif ?>

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

			<p><a href="signup.php">Not Registered? Sign Up</a></p>
		</div>
	<footer>
	</footer>
  </body>
</html>