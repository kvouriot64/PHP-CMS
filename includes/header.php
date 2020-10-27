<?php require 'db/connect.php';
include 'includes/message.php';
include 'query_categories.php';

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
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>AfterHours</title>
    <link rel="stylesheet" href="styles/bootstrap.css">
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
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
          <a class="navbar-brand" href="index.php">AfterHours</a>
          <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <ul class="navbar-nav mr-auto">
            <?php foreach($categories as $category): ?>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?category=<?= $category['CategoryID'] ?>"><?= $category['Category'] ?></a>
                </li>
            <?php endforeach ?>
          </ul>

          <form class="form-inline my-2 my-lg-0" action="search_results.php" method="post">
            <input class="form-control mr-sm-2" name="search" id="search" placeholder="Search">
            <button id="submit" type="submit" name="submit" class="btn btn-secondary my-2 my-sm-0">Search</button>
            <div class="form-group">
              <label class="form-control-label" for="category">Category</label>
              <select class="custom-select" name="category" id="category">
                <option value="all">All</option>
                <?php foreach($categories as $category): ?>
                  <option value="<?= $category['CategoryID'] ?>"><?= $category['Category'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </form>

          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= $message ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <?php if(!$userLoggedIn): ?>
                <a class="dropdown-item" href="login.php">Login</a>
              <?php else: ?>
                <a class="dropdown-item" href="logout.php">Log Out</a>
                <div class="dropdown-divider"></div>
              <?php endif ?>
              <?php if($adminLoggedIn): ?>
                <a class="dropdown-item" href="manage_categories.php">Manage Categories</a>
              	<a class="dropdown-item" href="admin.php">Manage Restaurants</a>
                <a class="dropdown-item" href="create.php" >Add Restaurant</a>
                <a class="dropdown-item" href="manage_users.php">Manage Users</a>
              <?php endif ?>
            </div>
          </div>
        </nav>
