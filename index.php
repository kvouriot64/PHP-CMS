<?php 
include 'includes/header.php';

if($_GET)
{
	$category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if($category)
	{
		$query = "SELECT * FROM Restaurant WHERE CategoryID = :id";

		$statement = $db->prepare($query);
		$statement->bindValue(':id', $category);
		$statement->execute();
		$posts = $statement->fetchAll();

		$rowCount = $statement->rowCount();
	}
}
else
{
	$query = "SELECT * FROM Restaurant";

	$statement = $db->prepare($query);
	$statement->execute();
	$posts = $statement->fetchAll();

	$rowCount = $statement->rowCount();
}
?>

<div id="all_restaurants">
  <h2>Welcome to AfterHours!</h2>
  <p>We're an open source hub for information on all bars and restaurants across Winnipeg! We allow users to browse and review restaurants as well as their menus and dishes.</p>

  <h3>Categories</h3>

  <?php foreach($categories as $category_result): ?>
      
    <p><a href="index.php?category=<?= $category_result['CategoryID'] ?>"><?= $category_result['Category'] ?></a>
    </p>

    <?php endforeach ?>

 <?php if($rowCount > 0): ?>
  <ul>
    <?php foreach($posts as $post): ?>
      
    <li><a href="restaurant.php?id=<?= $post['RestaurantId'] ?>"><?= $post['Name'] ?></a>
    </li>

    <?php endforeach ?>
  </ul>

  <?php else: ?>

  	<h2>Sorry, no results exist for that specified category.</h2>

  <?php endif ?>
</div>
<?php include 'includes/footer.php'; ?>