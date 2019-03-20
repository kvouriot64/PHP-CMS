<?php 
    include 'includes/header.php';

$query = "SELECT * FROM Restaurant";

$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll();
?>

<div id="all_restaurants">
  <h2>Welcome to AfterHours!</h2>
  <p>We're an open source hub for information on all bars and restaurants across Winnipeg! We allow users to browse and review restaurants as well as their menus and dishes.</p>

  <ul>
    <?php foreach($posts as $post): ?>
      
    <li><a href="restaurant.php?id=<?= $post['RestaurantId'] ?>"><?= $post['Name'] ?></a>
    </li>

    <?php endforeach ?>
  </ul>
</div>
<?php include 'includes/footer.php'; ?>