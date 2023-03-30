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

        if ($fetch != null){
        
            $fetchname = $fetch['userName'];
            $fetchPassword = $fetch['password'];
            $fetchAuth = $fetch['authorization'];
            $fetchid = $fetch['userId'];
        }
        else{
            $fetchname = "";
            $fetchPassword = "";
        }

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
            $_SESSION['loggedin'] = true;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>Document</title>
    <?php if (isset($_SESSION['user'])) : ?>
    <script type="text/javascript"> window.onload = function () { alert("Success!"); } </script>
    <?php endif ?>
</head>
<body>
    <?php include('header.php') ?>
        <div class="container">
            <div class="row">
                <div class="col">
                    <?php if ($error) : ?>
                        <h1 class="my-3">An error has occurred.</h1>
                        <?php foreach ($errorMessages as $errorMessage) : ?>
                            <p class="my-2"><?= $errorMessage ?> </p>
                        <?php endforeach ?>
                        <a class="home" href="login.php">Back</a>
                    <?php else : ?>
                        <div class="col-sm-4 me-4 my-2">
                            <form class="form-control" action="login.php" method="post">
                            <div class="row mb-3">
                                <label class="col-sm-1 me-auto col-form-label" for="username">Username:</label>
                                <div class="col-sm-9 my-2">
                                    <input class="col-sm-9" type="text" name="userName" id="userName">
                                </div>
                            </div>
                            <div class="row mb-3">
                            <label class="col-sm-1 me-auto col-form-label" for="password">Password:</label>
                                <div class="col-sm-9 my-1">
                                <input class="col-sm-9" type="password" name="password" id="password">
                            </div>
                        </div>
                        <button class="btn btn-outline-success my-1" type="submit">Login</button>
                        <p class="my-1 ms-2">No account? <a href="register.php">Register now!</a></p>
                        </form>
                        </div>
                    <?php endif ?>
                </div>   
                <?php include('aside.php') ?> 
            </div>
        </div>  
    </div>   
</body>
</html>