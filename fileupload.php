<?php

require_once 'db/connect.php';

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

            //insert the image into the database

            $image_query = "SELECT 1 FROM Images WHERE FileName = :file_name";
            $statement = $db->prepare($image_query);
            $statement->bindValue(':file_name', $image_filename);
            $statement->execute();

            if($statement->rowCount() == 0)
            {
                $image_insert = "INSERT INTO Images (FileName)
                                VALUES (:file)";

                $statement = $db->prepare($image_insert);
                $statement->bindValue(':file', $image_filename);
                $statement->execute();

                // pull the id of the image that was just added to the database
                $image_id_query = "SELECT MAX(ImageId) FROM Images";
                $image_id_statement = $db->prepare($image_id_query);
                $image_id_statement->execute();
            }
            else
            {
                $image_id_query = "SELECT ImageId FROM Images WHERE FileName = :file_name";

                $image_id_statement = $db->prepare($image_id_query);
                $image_id_statement->bindValue(':file_name', $image_filename);
                $image_id_statement->execute();
            }

            $image_id = $image_id_statement->fetch();

            /*This query will execute if a new restaurant is being added. Because the new restaurant ID isn't known, a SELECT MAX() statement will be executed to retrieve it.*/
            $restaurant_id_query = "SELECT MAX(RestaurantId) FROM Restaurant";
            $statement = $db->prepare($restaurant_id_query);
            $statement->execute();

            $restaurant_id = $statement->fetch();

            // update the image id in the restaurants table

            $update_image_id = "UPDATE Restaurant 
                                    SET ImageId = :image_id
                                    WHERE RestaurantId = :rest_id";

            $statement = $db->prepare($update_image_id);
            $statement->bindValue(':image_id', $image_id[0]);
            $statement->bindValue(':rest_id', $restaurant_id[0]);

            $statement->execute();
        }
    }
?>