<?php 
include 'includes/header.php';

$invalidPasswordLength = false;
$nonMatchingPasswords = false;
$invalidEmail = false;
$invalidUsername = false;

$username_error = "* Username cannot be empty";
$email_error = "* Invalid email entered";
$passwords_dont_match = "* Your entered passwords don't match";
$password_wrong_length = "* Password must be between 8 and 16 characters";

if($_POST)
{
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
	
	if(!$invalidUsername && !$nonMatchingPasswords  && !$invalidEmail && !$invalidPasswordLength)
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

			    <?php if($invalidUsername && $_POST): ?>
			      <p class="text-danger"><?= $username_error ?></p>
			    <?php endif?>

			    <div class="form-group">
			      <label for="email">Email:</label>
			      <input name="email" class="form-control" id="email" placeholder="Your Email">
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