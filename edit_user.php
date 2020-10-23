<?php  require 'includes/header.php'; 
/*
* Gets the user specified in the get parameter so any edits
* can be made to the user's info and updated in the database
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
  $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

  include 'includes/validate_users.php';

  if(!$duplicate_username && !$invalidUsername && !$nonMatchingPasswords  && !$invalidEmail && !$invalidPasswordLength)
  {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    // update the user's information in the database if all conditionals are
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
?>
<div>
  <form method="post">
    <fieldset>
      <legend>Edit User Information</legend>

      <div class="form-group">
        <label for="username" class="col-sm-2 col-form-label">User Name: </label>
        <div class="col-sm-4">
          <input name="username" id="username" class="form-control" value="<?=$post['user_name']?>" />
        </div>
      </div>

      <?php if($invalidUsername && $_POST): ?>
        <p class="text-danger"><?= $username_error ?></p>
      <?php endif?>

      <?php if($duplicate_username && $_POST): ?>
        <p class="text-danger"><?= $duplicate_name_error ?></p>
      <?php endif ?>

      <div class="form-group">
        <label for="email" class="col-sm-2 col-form-label">Email: </label>
        <input name="email" id="email" class="form-control" value="<?= $post['Email'] ?>">
      </div>

      <?php if($invalidEmail && $_POST): ?>
        <p class="text-danger"><?= $email_error ?></p>
      <?php endif?>

      <div class="form-group">
        <label for="password" class="col-sm-2 col-form-label">New Password: </label>
        <div class="col-sm-4">
          <input name="password" type="password" class="form-control" id="password" placeholder="Password">
        </div>
      </div>

      <div class="form-group">
         <label for="confirm-password" class="col-sm-2 col-form-label">Confirm Password: </label>
          <input name="confirm-password" type="password" class="form-control" id="confirm-password" placeholder="Password">
      </div>

      <?php if($invalidPasswordLength && $_POST): ?>
        <p class="text-danger"><?= $password_wrong_length ?></p>
      <?php endif?>

      <?php if($nonMatchingPasswords && $_POST): ?>
        <p class="text-danger"><?= $passwords_dont_match ?></p>
      <?php endif?>

        <input type="hidden" name="id" value="<?= $post['UserId'] ?>" />
        <input type="submit" name="command" value="Update" />
      <p><a href="manage_users.php">cancel</a></p>
    </fieldset>
  </form>
</div>
<?php include 'includes/footer.php' ?>