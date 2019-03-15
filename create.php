<?php require 'db/connect.php';
      require 'authenticate.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AfterHours</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Create Post</a></h1>
        </div> <!-- END div id="header" -->
<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" class='active'>Add Restaurant</a></li>
</ul> <!-- END div id="menu" -->
<div id="all_blogs">
  <form action="process_post.php" method="post">
    <fieldset>
      <legend>Add a Restaurant</legend>
      <p>
        <label for="name">Restaurant Name: </label>
        <input name="name" id="name" />
      </p>
      <p>
        <label for="description">Description: </label>
        <textarea name="description" id="description"></textarea>
      </p>
      <p>
        <label for="address">Address: </label>
        <input name="address" id="address">
      </p>
      <p>
        <label for="phone">Phone Number: </label>
        <input name="phone" id="phone">
      </p>
      <p>
        <label for="postal">Postal Code: </label>
        <input name="postal" id="postal">
      </p>
      <p>
        <input type="submit" name="command" value="Add" />
      </p>
    </fieldset>
  </form>
</div>
        <div id="footer">
            Copywrong 2019 - No Rights Reserved
        </div> <!-- END div id="footer" -->
    </div> <!-- END div id="wrapper" -->
</body>
</html>
