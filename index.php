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
    <link rel="stylesheet" href="main.css">
    <title>Party Finder</title>
</head>
<body>
    <h1><a href="index.php">Party Finder</a></h1>
    <div id="nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="lfp.php">Looking for Party</a></li>
            <?php if (isset($_SESSION['user'])) : ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="login.php">Login</a></li>
            <?php endif ?>
        </ul>
        <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
            <form action="create.php" method="post">
            <button type="submit" name="table" value="new">New Post</button> 
            </form>
        <?php endif ?> 
    </div>
    <?php while ($new = $statement->fetch()) : ?>
        <div class="news">
            <h2> <?= $new['title'] ?></h2>
            <p> <?= date("F d, Y, g:i a", strtotime($new['date'])) ?></p>
            <p>By: <a href="member.php?userId=<?= $new['userId'] ?>"><?= $new['userName'] ?></a></p>
            <p> <?= $new['content'] ?></p>
            <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                <form action="edit.php?newId=<?= $new['newId'] ?>" method="post">
                <button type="submit" name="table" value="new">Edit</button>
                </form>    
            <?php endif ?> 
        </div>
    <?php endwhile ?>

</body>
</html>