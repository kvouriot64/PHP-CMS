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
		<table>
			<thead>
				<th>User Name</th>
				<th>User Type</th>
				<th>Edit</th>
				<th>Delete</th>
			</thead>
		<?php foreach($users as $user): ?>
			<tr>
				<td><?= $user['user_name'] ?></td>
				<td><?= $user['UserType'] ?></td>
				<td><a href="edit_user.php?id=<?= $user['UserId'] ?>">edit</a></td>
				<td><a href="delete_user.php?id=<?= $user['UserId'] ?>">delete</a></td>
			</tr>
		<?php endforeach ?>
		</table>
	<?php endif ?>
</div>

<?php include 'includes/footer.php' ?>