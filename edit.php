<?php  require 'includes/header.php'; 
/*
* Gets the blog post specified in the get parameter so any edits
* can be made to the post and updated in the database
*/ 

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  $query = "SELECT * FROM Restaurant, Categories WHERE 
      RestaurantId = :id";

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
?>
<div id="all_blogs">
  <form action="process_post.php" method="post">
    <fieldset>
      <legend>Edit Restaurant Information</legend>
      <p>
        <label for="name">Name: </label>
        <input name="name" id="name" value="<?=$post['Name']?>" />
      </p>
      <p>
        <label for="content">Description: </label>
        <textarea name="description" id="content"><?= $post['Description'] ?></textarea>
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
        <label for="category">Category: </label>
        <select name="category" id="category">
          <option value="1">Restaurant</option>
          <option value="2">Bar</option>
        </select>
      </p>
      <p>
        <input type="hidden" name="id" value="<?= $post['RestaurantId'] ?>" />
        <input type="submit" name="command" value="Update" />
        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this page?')" />
      </p>
      <p><a href="admin.php">Cancel</a></p>
    </fieldset>
  </form>
</div>
<?php include 'includes/footer.php' ?>