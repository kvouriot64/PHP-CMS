<?php  require 'includes/header.php'; 
/*
* Gets the blog post specified in the get parameter so any edits
* can be made to the post and updated in the database
*/ 

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  $query = "SELECT * FROM Users WHERE UserId = :id";

  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

  $count = $statement->rowCount();

  if($count == 0)
  {
    Header("Location: index.php");
  }
  else
  {
    $post = $statement->fetch();
  }
}
else
{
  header('Location:index.php');
}

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
<div id="all_blogs">
  <form method="post">
    <fieldset>
      <legend>Edit Restaurant Information</legend>
      <p>
        <label for="username">User Name: </label>
        <input name="username" id="username" value="<?=$post['user_name']?>" />
      </p>
      <p>
        <label for="email">Email: </label>
        <input name="email" id="email" value="<?= $post['Email'] ?>">
      </p>
      <p>
         <label for="password">New Password: </label>
          <input name="password" type="password" class="form-control" id="password" placeholder="Password">
      </p>
      <p>
         <label for="confirm-password">Confirm Password: </label>
          <input name="confirm-password" type="password" class="form-control" id="confirm-password" placeholder="Password">
      </p>
      <p>
        <input type="hidden" name="id" value="<?= $post['UserId'] ?>" />
        <input type="submit" name="command" value="Update" />
      </p>
      <p><a href="manage_users.php">cancel</a></p>
    </fieldset>
  </form>
</div>
<?php include 'includes/footer.php' ?>