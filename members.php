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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>Document</title>
</head>
<body>
    <?php include('header.php') ?>
    <div class="container">
        <div class="row">
            <div class="col" id="content">
                <?php while ($user = $statement->fetch()) : ?>
                    <div class="users">
                        <h2> <a href="member.php?userId=<?= $user['userId'] ?>"><?= $user['userName'] ?></a></h2>  
                    </div>
                <?php endwhile ?>
            </div>   
            <?php include('aside.php') ?> 
        </div>
    </div>     
</body>
</html>