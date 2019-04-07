<?php include 'includes/header.php';

$searched_result = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$search_category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


$searched_result = "%$searched_result%";

if($search_category == 'all')
{
	$query = "SELECT * FROM Restaurant
			WHERE Description LIKE :search
			OR Name LIKE :search";

	$statement = $db->prepare($query);

	$statement->bindValue(':search', $searched_result);
}
elseif($search_category)
{
	$query = "SELECT * FROM Restaurant
			WHERE (Description LIKE :search
			OR Name LIKE :search)
			AND Restaurant.CategoryID = :id";
	$statement = $db->prepare($query);
	$statement->bindValue(':search', $searched_result);
	$statement->bindValue(':id', $search_category);
}
else
{
	Header('Location: index.php');
}

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
	<?php elseif($rowCount === 0 && !empty(str_replace('%', "", $searched_result))): ?>
		<p>No results found for <?= str_replace('%', "", $searched_result) ?></p>
	<?php else: ?>
		<p>No results found.</p>
	<?php endif ?>
</div>
<?php include 'includes/footer.php'; ?>