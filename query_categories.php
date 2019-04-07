<?php

$category_query = "SELECT * FROM Categories";

$category_statement = $db->prepare($category_query);
$category_statement->execute();
$categories = $category_statement->fetchAll();

?>