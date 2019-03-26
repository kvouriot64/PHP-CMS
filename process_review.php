<?php 
require 'db/connect.php';
session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  if($_POST)
  {
    $heading = filter_input(INPUT_POST, 'heading', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

    if($heading && $review && ($rating > 0 || $rating <= 5))
    {
      echo 'test';
      $query = "INSERT INTO reviews (Heading, Review, Rating, RestaurantId, UserId)
                    VALUES (:heading, :review, :rating, :restId, :userId)";

      $statement = $db->prepare($query);

      $statement->bindValue(':heading', $heading);
      $statement->bindValue(':review', $review);
      $statement->bindValue(':rating', $rating);
      $statement->bindValue(':restId', $id);
      $statement->bindValue(':userId', $_SESSION['UserId']);

      $statement->execute();

     Header('Location: restaurant.php?id=' . $id);
    }
  }
}
?>