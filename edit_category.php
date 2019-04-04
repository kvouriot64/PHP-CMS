<?php require 'authenticate.php';
include 'includes/header.php';

if(!$adminLoggedIn)
{
  Header("Location: index.php");
}
else
{

  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

  if($id)
  {
    $query = "SELECT * FROM Categories WHERE CategoryID = :id";

    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();

    $result = $statement->fetch();


    if($_POST)
    {
      $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      if($category)
      {
        $query = "UPDATE categories 
                    SET Category = :category
                    WHERE CategoryID = :id";

        $statement = $db->prepare($query);

        $statement->bindValue(':category', $category);
        $statement->bindValue(':id', $id);
        $statement->execute();

        Header("Location: manage_categories.php");
      }
    }
  }
  else
  {
    Header("Location: index.php");
  }
}
?>

<div id="all_restaurants">
  <?php if($result): ?>
    <div class="form-group">
        <label for="category">Category: </label>
        <input name="category" value="<?= $result['Category'] ?>" class="form-control" id="category">

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </div>
  <?php endif ?>
</div>
<?php include 'includes/footer.php'; ?>