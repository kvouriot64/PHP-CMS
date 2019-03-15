<?php require 'connect.php';
/*
* Shows an entire post on its own page - specifically intended for 
* posts longer than 200 characters in length
*/

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if($id)
{
  $query = "SELECT * FROM BlogPosts WHERE Id = (:id)";

  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id);
  $statement->execute();

  $post = $statement->fetch();
}
else
{
    header('Location:index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kyle's Blog - Show All</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Kyle's Blog</a></h1>
        </div> <!-- END div id="header" -->
<ul id="menu">
    <li><a href="index.php" >Home</a></li>
    <li><a href="create.php" >New Post</a></li>
</ul> <!-- END div id="menu" -->
  <div id="all_blogs">
    <div class="blog_post">
            <h2><a href="show.php?id=<?=$post['Id']?>"><?= $post['Title'] ?></a></h2>
              <p>
                <small>
                  <?= $post['PostDate'] ?> -
                  <a href="edit.php?id=<?=$post['Id']?>">edit</a>
                </small>
              </p>
            <div class='blog_content'><?= $post['Content'] ?></div>
          </div>
  </div>
        <div id="footer">
            Copywrong 2019 - No Rights Reserved
        </div> <!-- END div id="footer" -->
    </div> <!-- END div id="wrapper" -->
</body>
</html>