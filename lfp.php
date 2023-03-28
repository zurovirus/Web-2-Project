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
        $selectQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
                        p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN users e 
                        ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId ORDER BY postId DESC LIMIT 10";

        // Prepares the data for the query.
        $statement = $db->prepare($selectQuery);

        // Execute the SELECT.
        $statement->execute();
    }
    else{
        if (isset($_POST['sorted']))
        {
            $sort = $_POST['sort'];
            $order = $_POST['order'];
    
            // A select query based off the id in descending order up to 5 records.
            $selectQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
            p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN users e 
            ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId ORDER BY $sort $order LIMIT 10";

            // Prepares the data for the query.
            $statement = $db->prepare($selectQuery);
    
            // Execute the SELECT.
            $statement->execute();
        }

        if (isset($_POST['category']))
        {
            $categorySort = $_POST['category'];
            $search = $_POST['search'];

            $selectQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
                            p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN 
                            users e ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId  WHERE (p.content 
                            LIKE '%$search%' OR p.title LIKE '%$search%' OR u.userName LIKE '%$search%') AND p.categoryId $categorySort ORDER BY p.postId DESC";

            $statement = $db->prepare($selectQuery);
            $statement->execute();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>Party Finder</title>
</head>
<body>
    <?php include('header.php') ?>
    <div class="container">
        <div class="row">
            <div class="col" id="content">
                <?php if (isset($_SESSION['user'])) : ?>
                    <form action="create.php" method="post">
                    <button type="submit" name="table" value="post">New Post</button> 
                    </form>   
                    </br>
                <?php endif ?> 
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
                    <button type="submit" name="sorted" value="sort">Sort</button> 
                </form>
            <?php endif ?> 
            <?php if (isset($_POST['sorted'])) : ?>
                <p>Posts sorted by: <?= $sort ?> <?= $order ?></p>
            <?php endif ?>
            <?php while ($post = $statement->fetch()) : ?>
                <div class="posts">
                    <h2> <a href="post.php?postId=<?= $post['postId'] ?>"><?= $post['title'] ?></a></h2>
                    <p class="date"> Date created: <?= date("F d, Y, g:i a", strtotime($post['dateCreated'])) ?></p>
                    <p>By: <a href="member.php?userId=<?= $post['oUserId'] ?>"><?= $post['oUserName'] ?></a></p>
                    <p> <?= $post['content'] ?></p>
                    <?php if ($post['updated'] != null) : ?>
                        <p class="date"> Edit By: <a href="member.php?userId=<?= $post['eUserId'] ?>"><?= $post['eUserName'] ?></a> on <?= date("F d, Y, g:i a", strtotime($post['updated'])) ?></p>
                    <?php endif ?>
                    <?php if (isset($_SESSION['userId'])) : ?>
                        <?php if ($_SESSION['userId'] == $post['oUserId'] || $_SESSION['authorization'] >= 3) : ?>
                            <form action="edit.php?postId=<?= $post['oUserId'] ?>" method="post">
                            <button type="submit" name="table" value="post">Edit</button>
                            </form>     
                        <?php endif ?>
                    <?php endif ?>
                </div>
            <?php endwhile ?>
            </div>           
            <?php include('aside.php') ?>
        </div>
    </div> 
</body>
</html>