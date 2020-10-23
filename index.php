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
		$restaurants = $statement->fetchAll();

		$rowCount = $statement->rowCount();
	}
}
else
{
	$query = "SELECT * FROM Restaurant";

	$statement = $db->prepare($query);
	$statement->execute();
	$restaurants = $statement->fetchAll();

	$rowCount = $statement->rowCount();
}
?>

<div class="jumbotron">
  <h2 class="display-3">Welcome to AfterHours!</h2>
  <p>We're an open source hub for information on all bars and restaurants across Winnipeg! We allow users to browse and review restaurants as well.</p>
</div>
<div>
 <?php if($rowCount > 0): ?>
  <ul>
    <?php foreach($restaurants as $restaurant): ?>
      
    <li><a href="restaurant.php?id=<?= $restaurant['RestaurantId'] ?>"><?= $restaurant['Name'] ?></a>
    </li>

    <?php endforeach ?>
  </ul>

  <?php else: ?>

  	<h2>Sorry, no results exist for that specified category.</h2>

  <?php endif ?>
</div>
<?php include 'includes/footer.php'; ?>