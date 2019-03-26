<?php
	include 'includes/header.php';

	$query = "SELECT * FROM users";
	$statement = $db->prepare($query);
	$statement->execute();

	$users = $statement->fetchAll();
?>

<div>
	<?php if(count($users) > 0): ?>
		<?php foreach($users as $user): ?>

			<p><?= $user['user_name'] ?> <a href="delete_user.php?id=<?= $user['UserId'] ?>"></a></p>


		<?php endforeach ?>
	<?php endif ?>
</div>

</body>
</html>