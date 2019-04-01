<?php require 'db/connect.php';
/*
* Executes the necessary SQL commands to insert,
* update or delete blog posts
*/

if($_POST)
{
	$command = $_POST['command'];

	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

	//Insert commands
	if($command == 'Add')
	{
		//Sanitizes all the information to be added or
		//updated in the Restaurant table
		include 'includes/sanitizerestaurant.php';

		$validCategory = $category == 1 || $category == 2;
		
		if($name && $description && $address && $phone && $postal && $validCategory)
		{
			$createStatement = "INSERT INTO Restaurant (Name, Description, Address, PhoneNumber, PostalCode, CategoryID) VALUES (:title, :content, :address, :phone, :postal, :catID)";

			$statement = $db->prepare($createStatement);

			$statement->bindValue(':title', $name);
			$statement->bindValue(':content', $description);
			$statement->bindValue(':address', $address);
			$statement->bindValue(':phone', $phone);
			$statement->bindValue(':postal', $postal);
			$statement->bindValue(':catID', $category);

			$statement->execute();

			header('Location:index.php');
		}		
	}
	elseif($command == 'Update')
	{
		include 'includes/sanitizerestaurant.php';

		$validCategory = $category == 1 || $category == 2;

		if(!$id || !$name && !$description && !$address && !$phone && !$postal && !$validCategory)
		{
			header('Location:index.php'); //Sends the user back to the home page if the id isn't properly formatted
		}
		elseif($id && $name && $description && $address && $phone && $postal && $validCategory)
		{
			$query = "UPDATE Restaurant					
						SET Name = :name,
						Description = :description,
						Address = :address,
						PhoneNumber = :phonenumber,
						PostalCode = :postal,
						UpdateDate = CURRENT_TIMESTAMP,
						CategoryID = :catID
						WHERE RestaurantId = :id";

			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$statement->bindValue(':name', $name);
			$statement->bindValue(':description', $description);
			$statement->bindValue(':address', $address);
			$statement->bindValue(':phonenumber', $phone);
			$statement->bindValue(':postal', $postal);
			$statement->bindValue(':catID', $category);

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
			$query = "DELETE FROM Restaurant WHERE RestaurantId = :id";

			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$statement->execute();
		}
		header('Location:index.php');
	}
}
?>