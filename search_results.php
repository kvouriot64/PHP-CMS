<?php include 'includes/header.php';

$searched_result = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$searched_result = "%$searched_result%";

$query = "SELECT * FROM Restaurant
			WHERE Description LIKE :search
			OR Name LIKE :search";

$statement = $db->prepare($query);

$statement->bindValue(':search', $searched_result);
$statement->execute();

$results = $statement->fetchAll();
$rowCount = $statement->rowCount();
?>
<div>
	<?php if($rowCount > 0): ?>
		<div>
			<?php foreach($results as $result): ?>

				<h2><a href="restaurant.php?id=<?= $result['RestaurantId'] ?>"><?= $result['Name'] ?></a></h2>
				<p><?= $result['Description'] ?></p>

			<?php endforeach ?>
	<?php else: ?>
		<p>No results for <?= $searched_result ?></p>
	<?php endif ?>
</div>
<?php include 'includes/footer.php'; ?>