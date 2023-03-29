<?php
require('connect.php');

session_start();
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
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <meta http-equiv="refresh" content="3; url=index.php">
                    <p>Success!</p>
                    <p>Redirecting to the tavern...</p>
                <?php else : ?>
                    <meta http-equiv="refresh" content="3; url=login.php">
                    <p>Redirecting to the login page...</p>
                <?php endif ?>
                <?php if (isset($_SESSION['authorization'])) : ?>
                    <?= header("Location: index.php") ?>
                <?php endif ?>
            </div>   
            <?php include('aside.php') ?> 
        </div>
    </div>     
</body>
</html>