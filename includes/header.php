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
    <link rel="stylesheet" href="style.css" type="text/css">
    <script src="https://cloud.tinymce.com/5/tinymce.min.js"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">AfterHours</a></h1>
            <p><?= $message ?></p>
        </div>
          <ul id="menu">
              <li><a href="index.php" class='active'>Home</a></li>

              <?php if($adminLoggedIn): ?>
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