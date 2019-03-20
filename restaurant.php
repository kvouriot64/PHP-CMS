<?php require 'includes/header.php';
/*
* Shows an entire post on its own page - specifically intended for 
* posts longer than 200 characters in length
*/

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  $query = "SELECT * FROM Restaurant WHERE RestaurantId = (:id)";

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
    <div class="blog_post">
            <h2><?= $post['Name'] ?></h2>
            <p class='blog_content'>About <?= $post['Name'] ?>: <?= $post['Description'] ?></p>
            <p>Phone Number: <?= $post['PhoneNumber'] ?></p>
            <p>Address: <?= $post['Address'] ?>, <?= $post['PostalCode'] ?></p>
          </div>
  </div>
        <div id="footer">
            Copywrong 2019 - No Rights Reserved
        </div> <!-- END div id="footer" -->
    </div> <!-- END div id="wrapper" -->
</body>
</html>