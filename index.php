<?php require 'db/connect.php';

$query = "SELECT * FROM Restaurant ORDER BY Name";

$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AfterHours</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">AfterHours</a></h1>
        </div> 
          <ul id="menu">
              <li><a href="index.php" class='active'>Home</a></li>
              <li><a href="create.php" >Add Restaurant</a></li>
          </ul> 
<div id="all_restaurants">
  <ul>
    <?php foreach($posts as $post): ?>
      
      <li><a href="restaurant.php?id=<?= $post['RestaurantId'] ?>"><?= $post['Name'] ?></a></li><span><a href="edit.php?id=<?= $post['RestaurantId'] ?>">edit</a></span>

    <?php endforeach ?>
  </ul>
</div>
<?php include 'includes/footer.php'; ?>