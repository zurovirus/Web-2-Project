<?php 
/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   
    require('connect.php');
    session_start();

    // A select query based off the id in descending order up to 5 records.
    $selectQuery = "SELECT * FROM users ORDER BY userName ASC";

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
    <title>Document</title>
</head>
<body>
    <?php include('header.php') ?>
    <?php include('aside.php') ?>
    <?php while ($user = $statement->fetch()) : ?>
        <div class="users">
            <h2> <a href="member.php?userId=<?= $user['userId'] ?>"><?= $user['userName'] ?></a></h2>  
        </d iv>
    <?php endwhile ?>
</body>
</html>