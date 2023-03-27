<?php
    require('connect.php');

    $error = false;
    $errorMessages = [];
    if($_POST){

        $username = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($username)){
            $error = true;
            $errorMessages[] .= "Username cannot be empty.";
        }

        $usernamecheck = "SELECT * FROM users WHERE userName = :userName LIMIT 1";

        $statement = $db->prepare($usernamecheck);

        $statement->bindValue(':userName', $username, PDO::PARAM_STR);

        $statement->execute();

        $fetch = $statement->fetch();
        
        $fetchname = $fetch['userName'];
        $fetchPassword = $fetch['password'];
        $fetchAuth = $fetch['authorization'];
        $fetchid = $fetch['userId'];
        
        if ($fetchname != $username && !empty($username)) 
        {
            $error = true;
            $errorMessages[] .= "Username does not exist.";
        }
        
        if (empty($password)){
            $error = true;
            $errorMessages[] .= "Password cannot be empty.";
        }
        
        if (!password_verify($password, $fetchPassword) && !empty($password)){
            $error = true;
            $errorMessages[] .= "Incorrect password.";
        }

        if (!$error){
            session_start();
            $_SESSION['user'] = $fetchname;
            $_SESSION['authorization'] = $fetchAuth;
            $_SESSION['userId'] = $fetchid;

            header("Location: success.php");
            exit;
        }
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
    <?php if ($error) : ?>
        <h1>An error has occurred.</h1>
        <?php foreach ($errorMessages as $errorMessage) : ?>
            <p><?= $errorMessage ?> </p>
        <?php endforeach ?>
        <a class="home" href="login.php">Back</a>
    <?php else : ?>
        <li><p>No account? <a href="register.php">Register now!</a></p></li>
        <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="userName" id="userName">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <button type="submit">Login</button>
        </form>
    <?php endif ?>
</body>
</html>