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

			header('Location:index.php');
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
			
			// file_name by default is set to an empty string. If the check box to delete an existing image is set, file_name will pass the validation and the deleteImage function will execute
			if($file_name)
			{
				$image_id = getImageId($db, $file_name);
				deleteImage($image_id['ImageId'], $db, $file_name, $id);
			}

			header('Location:admin.php');
		}
	}
	elseif($command == 'Delete')
	{
		if(!$id)
		{
			header('Location:admin.php');
		}
		else
		{
			$file_query = "SELECT Images.ImageId, FileName FROM Images JOIN Restaurant
							ON Images.ImageId = Restaurant.ImageId 
							WHERE RestaurantId = :id";

			$file_statement = $db->prepare($file_query);
			$file_statement->bindValue(':id', $id);
			$file_statement->execute();

			if($file_statement->rowCount() > 0);
			{
				$file_name = $file_statement->fetch();
				deleteImage($file_name['ImageId'], $db, $file_name['FileName']);
			}
			
			$table = "Reviews";
			$primary_key = "RestaurantId";

			deleteStatement($db, $table, $primary_key, $id);

			$table = "Restaurant";
			deleteStatement($db, $table, $primary_key, $id);
		}
		header('Location:index.php');
	}
}

/*
* Executes delete statements to the database based on the table's primary key
* param $table: The table whose row is being delete
* param $table_id: The column being used to execute the delete statement
* param $id: The physical value of the $table_id column
* param $db: The PDO object representing our database.
*/
function deleteStatement($db, $table, $table_id, $id) {
	$query = "DELETE FROM $table WHERE $table_id = :id";

	$statement = $db->prepare($query);
	$statement->bindValue(':id', $id);
	$statement->execute();
}

/*Deletes a specific image from the database and uploads folder
* param $image_id: The ID of the image
* param $file_name: The name of the file to remove
* param $db: The database being modified.
* param $rest_id: The ID of the restaurant using the image specified
*/
function deleteImage($image_id, $db, $file_name, $rest_id) {
	deleteStatement($db, 'Images', 'ImageId', $image_id);

	$update_query = "UPDATE Restaurant
					SET ImageId = 0
					WHERE RestaurantId = :id";

	$update_statement = $db->prepare($update_query);
	$update_statement->bindValue(':id', $rest_id);
	$update_statement->execute();

	unlink("uploads/" . $file_name);
}

/*
* Gets the imageId based on the file name of an existing image
* params $db: The database the image is being extracted from
* params $image_filename: The file name of the image being looked for
* returns: returns the Id of the image from the database or null if the searched image doesn't exist
*/
function getImageId($db, $image_filename) {
	$image_name_query = "SELECT ImageId FROM Images WHERE FileName = :file_name";
    $image_statement = $db->prepare($image_name_query);
    $image_statement->bindValue(':file_name', $image_filename);
    $image_statement->execute();

    return $image_statement->fetch();
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
            // validate the restaurant id
         	if($id)
            {
                //Query to see if any rows exist that match the restaurant id
                //of the restaurant being edited, 1 is being used for efficiency purposes
                $query = "SELECT 1 FROM Images JOIN Restaurant ON Images.ImageId = Restaurant.ImageId WHERE RestaurantId = :id";

                $stmt = $db->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->execute();

                /*If there's no records returned, an insert statement will be executed in order for the update to properly work. If there is, a normal update statement is executed*/
                if($stmt->rowCount() == 0)
                {
                	$results = getImageId($db, $image_filename);
                	$image_id = $results['ImageId'];

                	if(!$image_id)
                	{
                		$query = "INSERT INTO Images (FileName)
                        		VALUES (:file)";
                    
                    	$statement = $db->prepare($query);
                		$statement->bindValue(':file', $image_filename);
                		$statement->execute();

                		$results = getImageId($db, $image_filename);
                		$image_id = $results['ImageId'];
                	}

                    $query = "UPDATE Restaurant
                    SET Restaurant.ImageId = :image_id
                    WHERE RestaurantId = :id";
                    $statement = $db->prepare($query);
                	$statement->bindValue(':image_id', $image_id);
                	$statement->bindValue(':id', $id);
                	$statement->execute();
                }
                else
                {
                	// if the image to update to already exists in the database, pull the Id and set that as the foreign key in the restaurants table
                	$results = getImageId($db, $image_filename);
                	$image_id = $results['ImageId'];

                    $query = "UPDATE Restaurant
                    SET Restaurant.ImageId = :image_id
                    WHERE RestaurantId = :id";
                    $statement = $db->prepare($query);
                	$statement->bindValue(':image_id', $image_id);
                	$statement->bindValue(':id', $id);
                	$statement->execute();
                }
            }
        }
    }
}
?>