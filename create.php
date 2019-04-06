<?php  require 'includes/header.php'; 


  $query = "SELECT * FROM Categories";

  $statement = $db->prepare($query);
  $statement->execute();

  $categories = $statement->fetchAll();
?>

<div id="all_blogs">
  <form action="process_post.php" method="post">
    <fieldset>
      <legend>Add a Restaurant</legend>
      <p>
        <label for="name">Restaurant Name: </label>
        <input name="name" id="name" />
      </p>
      <p>
        <label for="content">Description: </label>
        <textarea name="description" id="content"></textarea>
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
        <label for="category">Category: </label>
        <select name="category" id="category">
          <?php foreach($categories as $category): ?>
            <option value="<?= $category['CategoryID'] ?>"><?= $category['Category'] ?></option>
          <?php endforeach ?>
        </select>
      </p>
      <p>
        <input type="submit" name="command" value="Add" />
      </p>
      <p><a href="admin.php">Cancel</a></p>
    </fieldset>
  </form>
</div>
        <div id="footer">
            Copywrong 2019 - No Rights Reserved
        </div> <!-- END div id="footer" -->
    </div> <!-- END div id="wrapper" -->
</body>
</html>
