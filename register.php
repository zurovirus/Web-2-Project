<?php


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
    <form action="register.php" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username" id="username">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password">
        <label for="confirmpassword">Confirm Password: </label>
        <input type="password" name="confirmpassword" id="confirmpassword">
        <label for="name">Name: </label>
        <input type="text" name="name" id="name">   
        <label for="password">Email: </label>
        <input type="email" name="email" id="email">
    </form>
</body>
</html>