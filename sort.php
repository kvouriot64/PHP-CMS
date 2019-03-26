<?php

	$orderClause = filter_input(INPUT_GET, "sort", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	session_start();
	switch ($orderClause)
	{
		case 'UpdateDate':
		$_SESSION['orderclause'] = $orderClause . ' DESC';
		break;

		case 'CreateDate':
		$_SESSION['orderclause'] = $orderClause . ' DESC';
		break;

		default:
		$_SESSION['orderclause'] = $orderClause;
		break;
	}

	Header("Location: admin.php");
?>