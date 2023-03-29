<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   
    require('connect.php');

    session_start();

    // A select query based off the id in descending order up to 5 records.
    $selectQuery = "SELECT * FROM news INNER JOIN users ON users.userId = news.userId ORDER BY newId DESC LIMIT 5";

    // Prepares the data for the query.
    $statement = $db->prepare($selectQuery);

    // Execute the SELECT.
    $statement->execute();

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
                <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                        <form action="create.php" method="post">
                        <button type="submit" name="table" value="new">New Post</button> 
                        </form>
                    <?php endif ?> 
                <?php while ($new = $statement->fetch()) : ?>
                    <div class="my-4">
                        <h2 class="my-3"> <?= $new['title'] ?></h2>
                        <p class="my-1"> <?= date("F d, Y, g:i a", strtotime($new['date'])) ?></p>
                        <p class="my-1">By: <a class="text-decoration-none" href="member.php?userId=<?= $new['userId'] ?>"><?= $new['userName'] ?></a></p>
                        <p class="my-2"> <?= $new['content'] ?></p>
                        <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                            <form action="edit.php?newId=<?= $new['newId'] ?>" method="post">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-primary btn-sm"  type="submit" name="table" value="new">Edit</button>
                            </div>
                            </form>    
                        <?php endif ?> 
                    </div>
                <?php endwhile ?>
            </div>   
            <?php include('aside.php') ?> 
         </div>
    </div>             
</body>
</html>