<?php

	$orderClause = filter_input(INPUT_GET, "sort", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if($orderClause == 'Name' || $orderClause == 'CreateDate' || $orderClause == 'UpdateDate')
	{
		session_start();

		$_SESSION['orderclause'] = $orderClause;
	}

Header("Location: admin.php");
?>