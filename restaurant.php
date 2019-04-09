<?php require 'includes/header.php';
include 'fileupload.php';
/*
* Shows an entire post on its own page - specifically intended for 
* posts longer than 200 characters in length
*/

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  $query = "SELECT * FROM Categories JOIN Restaurant
              ON categories.CategoryID = restaurant.CategoryID 
              WHERE RestaurantId = (:id)";

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
  //Query for the images associated with this page.
  $image_query = "SELECT * FROM Images
                WHERE Images.RestaurantId = :id";
  $statement = $db->prepare($image_query);
  $statement->bindValue(':id', $id);
  $statement->execute();
  $image = $statement->fetch();
  $image_count = $statement->rowCount();

  $query = "SELECT ReviewId, reviews.UserId, Heading, PostDate, user_name, Review, Rating FROM reviews JOIN users ON reviews.UserId = users.UserId WHERE RestaurantId = :restId ORDER BY PostDate DESC";
  $select_statement = $db->prepare($query);
  $select_statement->bindValue(':restId', $id);
  $select_statement->execute();

  $reviews = $select_statement->fetchAll();
}
else
{
    header('Location:index.php');
}
?>

  <div id="all_blogs">
    <div class="blog_post">
            <?php if ($image_count > 0): ?>

              <img src="uploads/<?= $image['FileName'] ?>" alt="<?= str_replace(" ", "", $image['FileName']) ?>">
            
            <?php endif ?>
            <h2><?= $post['Name'] ?></h2>
            <h4>Category: <?= $post['Category'] ?></h4>
            <p class='blog_content'>About <?= $post['Name'] ?>:</p> <?= $post['Description'] ?>
            <p>Phone Number: <?= $post['PhoneNumber'] ?></p>
            <p>Address: <?= $post['Address'] ?>, <?= $post['PostalCode'] ?></p>
    </div>

    <div id="posted_reviews">
      <?php if(count($reviews) > 0): ?>

        <?php foreach($reviews as $review): ?>

          <h3><?= $review['Rating'] ?>/5</h3>
          <h3><?= $review['Heading'] ?></h3>
          <?= $review['Review'] ?>
          <small>Posted by <?= $review['user_name'] ?> at <?= $review['PostDate'] ?></small>

          <?php if($adminLoggedIn): ?>

            <p><a href="delete_review.php?id=<?= $review['ReviewId'] ?>&restid=<?= $id ?>">Delete Review</a></p>

          <?php endif ?>
        <?php endforeach ?>

      <?php endif ?>
    </div>

    <?php if($userLoggedIn): ?>
      <h1>Ate here recently? Leave a Review</h1>
      <div id="reviews">
        <form action="process_review.php?id=<?= $id ?>" method="post">
          <label for="heading">Heading:</label>
          <input name="heading" id="heading">

          <label for="content">Review: </label>
          <textarea name="review" id="content"></textarea>

          <label for="rating">Rating: </label>
          <select id="rating" name="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>

          <input type="submit" name="Command" value="Post">
        </form>
      </div>
  <?php endif ?>
</div>
<?php include 'includes/footer.php'; ?>