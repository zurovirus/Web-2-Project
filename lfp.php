<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   
    require('connect.php');
    session_start();

    if (!$_POST){
        // A select query based off the id in descending order up to 10 records.
        $selectQuery = "SELECT * FROM posts INNER JOIN users ON users.userId = posts.userId ORDER BY postId DESC LIMIT 10";
        $editQuery = "SELECT * FROM posts INNER JOIN users ON users.userId = posts.userEditId";

        $statement = $db->prepare($editQuery);

        $statement->execute();
        $editName = $statement->fetch();

        // Prepares the data for the query.
        $statement = $db->prepare($selectQuery);

        // Execute the SELECT.
        $statement->execute();
    }
    else{
        $sort = $_POST['sort'];
        $order = $_POST['order'];

        // A select query based off the id in descending order up to 5 records.
        $selectQuery = "SELECT * FROM posts INNER JOIN users ON users.userId = posts.userId ORDER BY $sort $order LIMIT 10";
        $editQuery = "SELECT * FROM posts INNER JOIN users ON users.userId = posts.userEditId";

        $statement = $db->prepare($editQuery);

        $statement->execute();
        $editName = $statement->fetch();


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
    <?php include('header.php') ?>
        <?php if (isset($_SESSION['user'])) : ?>
            <form action="create.php" method="post">
            <button type="submit" name="table" value="post">New Post</button> 
            </form>   
            </br>
        <?php endif ?> 
    </div>
    <?php if (isset($_SESSION['user'])) : ?>
        <form action="lfp.php" method="post">
        <select name="sort" id="sort">
            <option value="title">Title</option>
            <option value="dateCreated">Created</option>
            <option value="updated">Updated</option>
        </select>
        <select name="order" id="order">
            <option value="ASC">Oldest</option>
            <option value="DESC">Newest</option>
        </select>
            <button type="submit">Sort</button> 
        </form>
    <?php endif ?> 
    <?php if ($_POST) : ?>
        <p>Posts sorted by: <?= $sort ?> <?= $order ?></p>
    <?php endif ?>
    <?php while ($post = $statement->fetch()) : ?>
        <div class="posts">
            <h2> <a href="post.php?postId=<?= $post['postId'] ?>"><?= $post['title'] ?></a></h2>
            <p class="date"> Date created: <?= date("F d, Y, g:i a", strtotime($post['dateCreated'])) ?></p>
            <p>By: <a href="member.php?userId=<?= $post['userId'] ?>"><?= $post['userName'] ?></a></p>
            <p> <?= $post['content'] ?></p>
            <?php if ($post['updated'] != null) : ?>
                <p class="date"> Edit By: <a href="member.php?userId=<?= $post['userEditId'] ?>"><?= $post['userName'] ?></a> on <?= date("F d, Y, g:i a", strtotime($post['updated'])) ?></p>
            <?php endif ?>
            <?php if (isset($_SESSION['userId'])) : ?>
                <?php if ($_SESSION['userId'] == $post['userId'] || $_SESSION['authorization'] >= 3) : ?>
                    <form action="edit.php?postId=<?= $post['postId'] ?>" method="post">
                    <button type="submit" name="table" value="post">Edit</button>
                    </form>     
                <?php endif ?>
            <?php endif ?>
        </div>
    <?php endwhile ?>
</body>
</html>