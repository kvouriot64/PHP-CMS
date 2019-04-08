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

		if($name && $description && $address && $phone && $postal && $category)
		{
			$createStatement = "INSERT INTO Restaurant (Name, Description, Address, PhoneNumber, PostalCode, CategoryID)
								VALUES (:title, :content, :address, :phone, :postal, :catID)";
								
			$statement = $db->prepare($createStatement);

			$statement->bindValue(':title', $name);
			$statement->bindValue(':content', $description);
			$statement->bindValue(':address', $address);
			$statement->bindValue(':phone', $phone);
			$statement->bindValue(':postal', $postal);
			$statement->bindValue(':catID', $category);

			$statement->execute();

			include 'fileupload.php';

			Header('Location:index.php');
		}		
	}
	elseif($command == 'Update')
	{
		include 'includes/sanitizerestaurant.php';

		if(!$id || !$name && !$description && !$address && !$phone && !$postal && !$category)
		{
			header('Location:index.php'); //Sends the user back to the home page if the id isn't properly formatted
		}
		elseif($id && $name && $description && $address && $phone && $postal && $category)
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

			updateImages($id, $db);

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

/*Executes the necessary validation and SQL commands to update the images table.
* Param $id: Represents the ID of the restaurant that's being edited.
*/
function updateImages($id, $db) {


	// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
       // Build an array of paths segment names to be joins using OS specific slashes.
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
       // The DIRECTORY_SEPARATOR constant is OS specific.
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }


    // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/jpeg', 'image/png', 'image/gif'];
        $allowed_file_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = $_FILES['image']['type'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }


    $errorMessage = "Invalid file type.";
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);


    if ($image_upload_detected) 
    { 
        $image_filename        = $_FILES['image']['name'];
        $temporary_image_path  = $_FILES['image']['tmp_name'];
        $new_image_path        = file_upload_path($image_filename);

        if (file_is_an_image($temporary_image_path, $new_image_path)) 
        {
            move_uploaded_file($temporary_image_path, $new_image_path);

         	if($id)
            {
                //Query to see if any rows exist that match the restaurant id
                //of the restaurant being edited
                $query = "SELECT * FROM Images WHERE RestaurantId = :id";

                $stmt = $db->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();

                /*If there's no records returned, an insert statement will be executed in order for the update to properly work. If there is, a normal update statement is executed*/
                if($stmt->rowCount() == 0)
                {
                    $query = "INSERT INTO Images (FileName, RestaurantId)
                        		VALUES (:file, :id)";
                }
                else
                {
                    $query = "UPDATE Images 
                    SET FileName = :file
                    WHERE RestaurantId = :id";
                }

                $statement = $db->prepare($query);
                $statement->bindValue(':file', $image_filename);
                $statement->bindValue(':id', $id);
                $statement->execute();
            }
        }
    }
}
?>