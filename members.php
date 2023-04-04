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
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Document</title>
</head>
<body class="bg-image" style="background-image: url('images/board.jpg');">
    <?php include('header.php') ?>
    <?php include('login.php') ?>
        <div class="col">
            <div class="bg-image" style="background-image: url('images/post.png');">
                <h1 class="my-4 text-center fw-bolder">User List</h1>
            </div>
            <div class="container">
                <div class="row">
                    <?php while ($user = $statement->fetch()) : ?>
                        <div class="col-sm-4 mt-4 text-center my-2">
                            <h4 class="bg-image" style="background-image: url('images/ParchList.png');">
                            <a class="text-decoration-none" href="member.php?userId=<?= $user['userId'] ?>"><?= $user['userName'] ?></a></h4>  
                        </div>  
                    <?php endwhile ?>
                </div>
            </div>
        </div>
        <?php include('aside.php') ?> 
        </div>
    </div>     
</body>
</html>