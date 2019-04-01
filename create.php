<?php  require 'includes/header.php'; ?>

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
        <label for="category">Category: </label>
        <select name="category" id="category">
          <option value="1">Restaurant</option>
          <option value="2">Bar</option>
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
