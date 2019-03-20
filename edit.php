<?php  require 'includes/header.php'; 
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