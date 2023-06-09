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
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Party Finder</title>
</head>
<body class="bg-image w100" style="background-image: url('images/board.jpg');">
    <?php include('header.php') ?>
    <?php include('login.php') ?>
            <div class="col">
                <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                        <form action="create.php" method="post">
                        <button class="btn btn-warning btn mt-4" type="submit" name="table" value="new">New Post</button> 
                        </form>
                    <?php endif ?> 
                <?php while ($new = $statement->fetch()) : ?>
                    <div class="rounded my-4">
                        <div class="bg-image" style="background-image: url('images/ParchmentCenter.png');">
                            <div class="col ms-5 me-5">
                                <div class="row ms-5 me-5">
                                    <h2 class="my-3 text-center" id="title"> <?= $new['title'] ?></h2>
                                    <p class="my-1"> <?= date("F d, Y, g:i a", strtotime($new['date'])) ?></p>
                                    <p class="my-1">By: <a class="text-decoration-none" href="member.php?userId=<?= $new['userId'] ?>"><?= $new['userName'] ?></a></p>
                                    <p class="my-2"> <?= $new['content'] ?></p>
                                    <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                                        <form action="edit.php?newId=<?= $new['newId'] ?>" method="post">
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-outline-primary btn-sm my-2"  type="submit" name="table" value="new">Edit</button>
                                            </div>
                                        </form>    
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