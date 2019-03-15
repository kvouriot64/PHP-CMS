<?php 
require 'authenticate.php';
require 'db/connect.php';
/*
* Gets the blog post specified in the get parameter so any edits
* can be made to the post and updated in the database
*/ 

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  $query = "SELECT * FROM Restaurant WHERE RestaurantId = :id";

  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

  $post = $statement->fetch();
}
else
{
  header('Location:index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AfterHours - Edit</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Edit Information</a></h1>
        </div> <!-- END div id="header" -->
<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->
<div id="all_blogs">
  <form action="process_post.php" method="post">
    <fieldset>
      <legend>Edit Restaurant Information</legend>
      <p>
        <label for="name">Name: </label>
        <input name="name" id="name" value="<?=$post['Name']?>" />
      </p>
      <p>
        <label for="description">Description: </label>
        <textarea name="description" id="description"><?= $post['Description'] ?></textarea>
      </p>
      <p>
        <label for="address">Address</label>
        <textarea name="address" id="address"><?= $post['Address'] ?></textarea>
      </p>
      <p>
        <label for="phone">Phone Number: </label>
        <textarea name="phone" id="phone"><?= $post['PhoneNumber'] ?></textarea>
      </p>
      <p>
        <label for="postal">Postal Code: </label>
        <textarea name="postal" id="postal"><?= $post['PostalCode'] ?></textarea>
      </p>
      <p>
        <input type="hidden" name="id" value="<?= $post['RestaurantId'] ?>" />
        <input type="submit" name="command" value="Update" />
        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this page?')" />
      </p>
    </fieldset>
  </form>
</div>
<?php include 'includes/footer.php' ?>