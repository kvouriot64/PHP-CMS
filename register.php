<?php 
include 'includes/header.php';
include 'includes/validate_users.php';
	
if(!$duplicate_username && !$invalidUsername && !$nonMatchingPasswords  && !$invalidEmail && !$invalidPasswordLength)
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
?>

    <h1>Register</h1>
			<form method="post">
			  <fieldset>
			    <div class="form-group">
			      <label for="username">Username:</label>
			      <input name="username" type="text" class="form-control" id="username" placeholder="username">
			    </div>

			    <?php if($invalidUsername && $_POST): ?>
			      <p class="text-danger"><?= $username_error ?></p>
			    <?php endif?>

			    <?php if($duplicate_username && $_POST): ?>
			    	<p class="text-danger"><?= $duplicate_name_error ?></p>
			    <?php endif ?>

			    <div class="form-group">
			      <label for="email">Email:</label>
			      <input name="email" class="form-control" id="email" placeholder="email@example.com">
			    </div>

			     <?php if($invalidEmail && $_POST): ?>
			      <p class="text-danger"><?= $email_error ?></p>
			    <?php endif?>


			    <div class="form-group">
			      <label for="password">Password:</label>
			      <input name="password" type="password" class="form-control" id="password" placeholder="Password">
			    </div>

			    <div class="form-group">
			      <label for="confirm-password">Confirm Password:</label>
			      <input name="confirm-password" type="password" class="form-control" id="confirm-password" placeholder="Password">
			    </div>
			    <?php if($invalidPasswordLength && $_POST): ?>
			      <p class="text-danger"><?= $password_wrong_length ?></p>
			    <?php endif?>

			    <?php if($nonMatchingPasswords && $_POST): ?>
			      <p class="text-danger"><?= $passwords_dont_match ?></p>
			    <?php endif?>

			    <button id="submit" type="submit" name="submit" class="btn btn-primary">Submit</button>
			  </fieldset>
			</form>
<?php include 'includes/footer.php'; ?>