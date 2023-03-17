<?php
    session_destroy();
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
<h1><a href="index.php">Party Finder</a></h1>
        <div id="nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="lfp.php">Looking for Party</a></li>
                <?php if (isset($_SESSION)) : ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else : ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif ?>
            </ul>     
        </div>
    <p>You have been logged out.</p>
    <p><a class="home" href="index.php">Return home</a></p>
</body>
</html>