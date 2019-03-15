<?php require 'db/connect.php';
/*
* Executes the necessary SQL commands to insert,
* update or delete blog posts
*/

if($_POST)
{
	$command = $_POST['command'];
	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
	
	if($command == 'Add')
	{
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$postal = filter_input(INPUT_POST, 'postal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if($name && $description && $address && $phone && $postal)
		{
			$createStatement = "INSERT INTO Restaurant (Name, Description, Address, PhoneNumber, PostalCode) VALUES (:title, :content, :address, :phone, :postal)";

			$statement = $db->prepare($createStatement);

			$statement->bindValue(':title', $name);
			$statement->bindValue(':content', $description);
			$statement->bindValue(':address', $address);
			$statement->bindValue(':phone', $phone);
			$statement->bindValue(':postal', $postal);

			$statement->execute();

			header('Location:index.php');
		}
	}
	elseif($command == 'Update')
	{
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if(!$id)
		{
			header('Location:index.php');
		}
		elseif($id && $title && $content)
		{
			$query = "UPDATE BlogPosts 
					SET Title = :title,
					Content = :content,
					PostDate = CURRENT_TIMESTAMP
					WHERE Id = (:id)";

			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$statement->bindValue(':title', $title);
			$statement->bindValue(':content', $content);

			$statement->execute();
			header('Location:index.php');
		}
	}
	elseif($command == 'Delete')
	{
		if(!$id)
		{
			header('Location:index.php');
		}
		else
		{
			$query = "DELETE FROM BlogPosts WHERE Id =(:id)";

			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$statement->execute();
		}
		header('Location:index.php');
	}
}
?>

<?php if(!$title || !$content): ?>
<!DOCTYPE html>
<html>
<head>
	<title>Error</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="wrapper">
		<div id="all_blogs">
			<h1>An Error Occured</h1>
			<p>The content and title both need at least one character</p>
			<p><a href="index.php">Home</a></p>
		</div>
		<div id="footer">
	        Copywrong 2019 - No Rights Reserved
	    </div>
	</div>
</body>
</html>
<?php endif ?>