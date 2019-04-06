<?php include 'includes/header.php';

if(!$adminLoggedIn)
{
  Header("Location: index.php");
}

if(isset($_SESSION['orderclause']))
{
  $query = "SELECT * FROM Restaurant ORDER BY " . $_SESSION['orderclause'];
}
else
{
  $query = "SELECT * FROM Restaurant";
}

$statement = $db->prepare($query);
$statement->execute();
$posts = $statement->fetchAll();
?>

<div id="all_restaurants">
  <p>Sort by <a href="sort.php?sort=CreateDate">Date Created</a></p>
  <p>Sort by <a href="sort.php?sort=UpdateDate">Last Updated</a></p>
  <p>Sort by <a href="sort.php?sort=Name">Name</a></p>
  <ul>
    <?php foreach($posts as $post): ?>
      
    <li><a href="restaurant.php?id=<?= $post['RestaurantId'] ?>"><?= $post['Name'] ?></a>
 
        <?php if($adminLoggedIn): ?>
          <a href="edit.php?id=<?= $post['RestaurantId'] ?>">edit</a>
        <?php endif ?>

    </li>

    <?php endforeach ?>
  </ul>
</div>
<?php include 'includes/footer.php'; ?>