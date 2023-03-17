<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   
    require('connect.php');

    if (!$_POST){
        // A select query based off the id in descending order up to 5 records.
        $selectQuery = "SELECT * FROM posts ORDER BY postId DESC LIMIT 10";

        // Prepares the data for the query.
        $statement = $db->prepare($selectQuery);

        // Execute the SELECT.
        $statement->execute();
    }
    else{
        $sort = $_POST['sort'];
        $order = $_POST['order'];

        // A select query based off the id in descending order up to 5 records.
        $selectQuery = "SELECT * FROM posts ORDER BY $sort $order LIMIT 10";

        // Prepares the data for the query.
        $statement = $db->prepare($selectQuery);

        // Execute the SELECT.
        $statement->execute();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="main.css">
    <title>Party Finder</title>
</head>
<body>
    <h1><a href="index.php">Party Finder</a></h1>
    <div id="nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="lfp.php">Looking for Party</a></li>
        </ul>
        <form action="create.php" method="post">
        <button type="submit" name="table" value="post">New Post</button> 
        </form>   
    </div>
    <form action="lfp.php" method="post">
    <select name="sort" id="sort">
        <option value="title">Title</option>
        <option value="dateCreated">Created</option>
        <option value="updated">Updated</option>
    </select>
    <select name="order" id="order">
        <option value="ASC">Newest</option>
        <option value="DESC">Oldest</option>
    </select>
        <button type="submit">Sort</button> 
    </form>
    <?php if ($_POST) : ?>
        <p>Posts sorted by: <?= $sort ?> <?= $order ?></p>
    <?php endif ?>
    <?php while ($post = $statement->fetch()) : ?>
        <div class="posts">
            <h2> <a href="post.php?postId=<?= $post['postId'] ?>"><?= $post['title'] ?></a></h2>
            <p class="date"> Date created: <?= date("F d, Y, g:i a", strtotime($post['dateCreated'])) ?></p>
            <p> <?= $post['content'] ?></p>
            <?php if ($post['updated'] != null) : ?>
                <p class="date"> Date edited: <?= date("F d, Y, g:i a", strtotime($post['updated'])) ?></p>
            <?php endif ?>
            <form action="edit.php?postId=<?= $post['postId'] ?>" method="post">
            <button type="submit" name="table" value="post">Edit</button>
            </form>     
        </div>
    <?php endwhile ?>

</body>
</html>