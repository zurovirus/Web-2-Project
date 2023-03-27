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
    <title>Document</title>
</head>
<body>
<?php include('header.php') ?>
    <p>You have been logged out.</p>
    <p><a class="home" href="index.php">Return home</a></p>
</body>
</html>