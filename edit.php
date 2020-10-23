<?php  require 'includes/header.php'; 
/*
* Gets the restaurant specified in the get parameter so any edits
* can be made to the post and updated in the database
*/ 

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// check if the ID is valid
if($id)
{
  $query = "SELECT * FROM Restaurant, Categories WHERE 
      RestaurantId = :id";

  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

  $count = $statement->rowCount();

  // validates whether the id in the get parameter is valid or not
  if($count == 0)
  {
    Header("Location: index.php");
  }
  else
  {
    $restaurant = $statement->fetch();
  }

  $categoryquery = "SELECT * FROM Categories";

  $category_statement = $db->prepare($categoryquery);
  $category_statement->execute();
  $categories = $category_statement->fetchAll();

  $image_query = "SELECT Images.ImageId, FileName FROM Images JOIN Restaurant
                  ON Images.ImageId = Restaurant.ImageId 
                  WHERE RestaurantId = :id";

  $stmt = $db->prepare($image_query);
  $stmt->bindValue(':id', $id);
  $stmt->execute();

  $image = $stmt->fetch();
}
else
{
  header('Location:index.php');
}
?>
<div id="all_blogs">
  <form action="process_post.php" method="post" enctype='multipart/form-data'>
    <fieldset>
      <legend>Edit Restaurant Information</legend>

      <?php if ($stmt->rowCount() > 0): ?>
            <p>
              <img src="uploads/<?= $image['FileName'] ?>" alt="<?= str_replace(" ", "", $image['FileName']) ?>">

              <label for="check">Delete Image</label>
              <input type="checkbox" name="delete_image" id="check" value="<?= $image['FileName'] ?>">
            </p>
            <?php endif ?>

      <p>
        <label for="name">Name: </label>
        <input name="name" id="name" value="<?=$restaurant['Name']?>" />
      </p>
      <p>
        <label for="content">Description: </label>
        <textarea name="description" id="content"><?= $restaurant['Description'] ?></textarea>
      </p>
      <p>
        <label for="address">Address</label>
        <textarea name="address" id="address"><?= $restaurant['Address'] ?></textarea>
      </p>
      <p>
        <label for="phone">Phone Number: </label>
        <textarea name="phone" id="phone"><?= $restaurant['PhoneNumber'] ?></textarea>
      </p>
      <p>
        <label for="postal">Postal Code: </label>
        <textarea name="postal" id="postal"><?= $restaurant['PostalCode'] ?></textarea>
      </p>
      <p>
        <label for="category">Category: </label>
         <select name="category" id="category">
          <?php foreach($categories as $category): ?>
            <option value="<?= $category['CategoryID'] ?>"><?= $category['Category'] ?></option>
          <?php endforeach ?>
        </select>
      </p>
      <p>
        <label for='image'>Picture:</label>
        <input type='file' name='image' id='image'>
      </p>
      <p>
        <input type="hidden" name="id" value="<?= $restaurant['RestaurantId'] ?>" />
        <input type="submit" name="command" value="Update" />
        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this page?')" />
      </p>
      <p><a href="admin.php">Cancel</a></p>
    </fieldset>
  </form>
</div>
<?php include 'includes/footer.php' ?>