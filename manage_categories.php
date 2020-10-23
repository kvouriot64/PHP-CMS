<?php
include 'includes/header.php';

if($_POST)
{
	$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if($category)
	{
		$insert_query = "INSERT INTO categories (Category) VALUES (:category)";

		$insert_statement = $db->prepare($insert_query);

		$insert_statement->bindValue(':category', $category);
		$insert_statement->execute();
	}
}

$query = "SELECT * FROM categories";

$statement = $db->prepare($query);
$statement->execute();

$results = $statement->fetchAll();
?>

<div>
	<div>
		<h2>Existing Categories</h2>
		<ul>
			<?php foreach($results as $categorytype): ?>
				<li><?= $categorytype['Category'] ?> <a href="edit_category.php?id=<?= $categorytype['CategoryID'] ?>">edit</a></li>
			<?php endforeach ?>
		</ul>
	</div>
	<div class="container">
	<form method="post">
	  <fieldset>
	    <div class="form-group">
	      <label for="category">New Category:</label>
	      <input name="category" type="text" class="form-control" id="category">
	    </div>

	    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
	  </fieldset>
	</form>
	</div>
</div>

<?php include 'includes/footer.php'; ?>