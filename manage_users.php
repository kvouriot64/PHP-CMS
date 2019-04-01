<?php
	include 'includes/header.php';

	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();

	$users = $statement->fetchAll();
?>
<p><a href="add_user.php">Add User</a></p>
<div>
	<?php if(count($users) > 0): ?>
		<?php foreach($users as $user): ?>

			<p><?= $user['user_name'] ?> <a href="edit_user.php?id=<?= $user['UserId'] ?>">edit</a> <a href="delete_user.php?id=<?= $user['UserId'] ?>">delete</a></p>


		<?php endforeach ?>
	<?php endif ?>
</div>

<?php include 'includes/footer.php' ?>