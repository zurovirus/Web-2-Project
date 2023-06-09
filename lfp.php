<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   
    require('connect.php');
    session_start();

    if (!$_POST){
        if(isset($_GET['categoryId'])){
            
            $id = filter_input(INPUT_GET, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
            
            if ($id == 1){
                $getQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
                            p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN 
                            users e ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId  WHERE p.categoryId >= $id ORDER BY p.postId DESC";
            }
            else{
                $getQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
                                        p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN 
                                        users e ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId  WHERE p.categoryId = $id ORDER BY p.postId DESC";
            }
            $statement = $db->prepare($getQuery);

            $statement->execute();

            $count = $statement->rowCount();

            if ($id != $_GET['categoryId']){
                header("Location: index.php");
                exit;
            }
        }
        else{
            $selectQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
                            p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN users e 
                            ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId ORDER BY postId DESC LIMIT 10";

            $statement = $db->prepare($selectQuery);

            $statement->execute();

            $count = $statement->rowCount();
        }
    }
    else{
        if (isset($_POST['sorted'])){
            $sort = $_POST['sort'];
            $order = $_POST['order'];
    
            $selectQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
            p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN users e 
            ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId ORDER BY $sort $order LIMIT 10";

            $statement = $db->prepare($selectQuery);
    
            $statement->execute();

            $count = $statement->rowCount();
        }

        if (isset($_POST['category'])){
            $categorySort = $_POST['category'];
            $search = $_POST['search'];

            $selectQuery = "SELECT p.userId AS oUserId, u.userName AS oUserName, e.userId AS eUserId, e.userName AS eUserName,
                            p.title, p.postId, p.content, p.dateCreated, p.updated FROM posts p LEFT OUTER JOIN 
                            users e ON e.userId = p.userEditId LEFT OUTER JOIN users u ON u.userId = p.userId  WHERE (p.content 
                            LIKE '%$search%' OR p.title LIKE '%$search%' OR u.userName LIKE '%$search%') AND p.categoryId $categorySort ORDER BY p.postId DESC";

            $statement = $db->prepare($selectQuery);
            $statement->execute();

            $count = $statement->rowCount();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Party Finder</title>
</head>
<body class="bg-image" style="background-image: url('images/board.jpg');">
    <?php include('header.php') ?>
    <?php include('login.php') ?>
            <div class="col">
                <?php if (isset($_SESSION['user'])) : ?>
                    <form action="create.php" method="post">
                    <button class="btn btn-warning btn mt-4" type="submit" name="table" value="post">New Post</button> 
                    </form>   
                    </br>
                <?php endif ?> 
                <?php if (isset($_SESSION['user']) && $count > 1) : ?>
                    <form class="input-group ms-auto" action="lfp.php" method="post">
                    <select class="form-select me-2" name="sort" id="sort">
                        <option value="title">Title</option>
                        <option value="dateCreated">Created</option>
                        <option value="updated">Updated</option>
                    </select>
                    <select class="form-select me-2" name="order" id="order">
                        <option value="ASC">Oldest</option>
                        <option value="DESC">Newest</option>
                    </select>
                        <button class="btn btn-primary my-sm-0" type="submit" name="sorted" value="sort">Sort</button> 
                    </form>
                    <?php if (isset($_POST['sorted'])) : ?>
                        <label class="form-label mx-auto my-2"for="sort">Posts sorted by: <?= $sort ?> <?= $order ?></label>
                    <?php endif ?>
                <?php endif ?> 
                <?php if (isset($count) && $count == 0) : ?>
                    <h2>No results found.</h2>
                <?php endif ?>
                <?php while ($post = $statement->fetch()) : ?>
                    <div class="rounded my-4">
                        <div class="bg-image" style="background-image: url('images/ParchmentCenter.png');">
                            <div class="col ms-5 me-5">
                                <div class="row ms-5 me-5">
                                    <h2 class="my-3 text-center"> <a class="text-decoration-none" href="post.php?postId=<?= $post['postId'] ?>"><?= $post['title'] ?></a></h2>
                                    <p class="my-1"> Date created: <?= date("F d, Y, g:i a", strtotime($post['dateCreated'])) ?></p>
                                    <p class="my-1">By: <a class="text-decoration-none" href="member.php?userId=<?= $post['oUserId'] ?>"><?= $post['oUserName'] ?></a></p>
                                    <p class="my-2"> <?= $post['content'] ?></p>
                                    <?php if ($post['updated'] != null) : ?>
                                        <p class="my-2"> Edit By: <a class="text-decoration-none" href="member.php?userId=<?= $post['eUserId'] ?>"><?= $post['eUserName'] ?></a> on <?= date("F d, Y, g:i a", strtotime($post['updated'])) ?></p>
                                    <?php endif ?>
                                    <?php if (isset($_SESSION['userId'])) : ?>
                                        <?php if ($_SESSION['userId'] == $post['oUserId'] || $_SESSION['authorization'] >= 3) : ?>
                                            <form action="edit.php?postId=<?= $post['postId'] ?>" method="post">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-outline-primary btn-sm my-2" type="submit" name="table" value="post">Edit</button>
                                            </div>
                                            </form>     
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile ?>
            </div>           
            <?php include('aside.php') ?>
        </div>
    </div> 
</body>
</html>