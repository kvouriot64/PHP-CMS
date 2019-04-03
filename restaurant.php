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

  $count = $statement->rowCount();

  if($count == 0)
  {
    Header("Location: index.php");
  }
  else
  {
    $post = $statement->fetch();
  }

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
            <h2><?= $post['Name'] ?></h2>
            <p class='blog_content'>About <?= $post['Name'] ?>: <?= $post['Description'] ?></p>
            <p>Phone Number: <?= $post['PhoneNumber'] ?></p>
            <p>Address: <?= $post['Address'] ?>, <?= $post['PostalCode'] ?></p>
    </div>

    <div id="posted_reviews">
      <?php if(count($reviews) > 0): ?>

        <?php foreach($reviews as $review): ?>

          <h3><?= $review['Rating']?>/5</h2>
          <h3><?= $review['Heading'] ?></h2>
          <p><?= $review['Review'] ?></p>
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


<?php include 'includes/footer.php'; ?>