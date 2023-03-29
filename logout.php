<?php
    session_start();

    if (isset($_SESSION['user'])){
        session_destroy();
        header('Location: index.php');
    }
    else{
        header('Location: index.php');
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
    <title>Document</title>
</head>
<body>
<?php include('header.php') ?>
<div class="container">
    <div class="row">
        <div class="col" id="content">
            <p>You have been logged out.</p>
            <p><a class="home" href="index.php">Return home</a></p>
        </div>   
    <?php include('aside.php') ?> 
    </div>
</div>     
</body>
</html>