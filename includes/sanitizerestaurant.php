<?php 
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$description = $_POST['description'];

		$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$phone = filter_input(INPUT_POST, 'phone', FILTER_VALIDATE_INT);

		$postal = filter_input(INPUT_POST, 'postal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);