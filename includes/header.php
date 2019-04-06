<?php require 'db/connect.php';
include 'includes/message.php';

$adminLoggedIn = false;
$userLoggedIn = false;

if($_SESSION)
{
  $userQuery = "SELECT UserType FROM users WHERE user_name = :user";

  $userStatement = $db->prepare($userQuery);
  $userStatement->bindValue(':user', $_SESSION['username']);
  $userStatement->execute(); 

  $result = $userStatement->fetch();

  if(count($result) > 0)
  {
    $userLoggedIn = true;

    if($result['UserType'] == "Admin")
    {
      $adminLoggedIn = true;
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AfterHours</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.js"></script>
    <!-- <script src="js/loginValidate.js"></script> -->
<script>
     $(document).ready(function() {
  $('#content').summernote({
        height: 300})
});
    </script>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">AfterHours</a></h1>
            <p><?= $message ?></p>
        </div>
        <nav class="navbar">
          <ul>
              <li><a href="index.php" class='active'>Home</a></li>

              <?php if($adminLoggedIn): ?>
                <li><a href="manage_categories.php">Manage Categories</a></li>
              	<li><a href="admin.php">Admin</a></li>
                <li><a href="create.php" >Add Restaurant</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
              <?php endif ?>

              <?php if(!$userLoggedIn): ?>
              <li><a href="login.php">Login</a></li>
              <?php else: ?>
                <li><a href="logout.php">Log Out</a></li>
              <?php endif ?>
          </ul>
        </nav>

        <form action="search_results.php"class="searchbar" method="post">
          <input name="search" id="search" placeholder="Search">
           <button id="submit" type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>