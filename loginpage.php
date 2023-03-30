<?php
    require('connect.php');

    $userError = false;
    $passwordError = false;

    if($_POST){
        
        $username = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (empty($username)){
            $userError = true;
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
            $userError = true;
        }
        
        if (empty($password)){
            $passwordError = true;
        }
        
        if (!password_verify($password, $fetchPassword) && !empty($password)){
            $passwordError = true;
        }

        if (!$passwordError && !$userError){
            session_start();
            $_SESSION['user'] = $fetchname;
            $_SESSION['authorization'] = $fetchAuth;
            $_SESSION['userId'] = $fetchid;
            $_SESSION['loggedin'] = true;

            header("Location: index.php");
            
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
    <?php include('login.php') ?>
                <div class="col">
                        <div class="col-sm-4 me-4 my-2">
                            <form class="form-control" action="login.php" method="post">
                            <div class="row mb-3">
                                <label class="col-sm-1 me-auto col-form-label" for="username">Username:</label>
                                <div class="col-sm-9 my-2">
                                    <input class="col-sm-9" type="text" name="userName" id="userName">
                                </div>
                                <?php if ($userError) : ?>
                                    <p class="error">Invalid Username</p>
                                <?php endif ?>
                            </div>
                            <div class="row mb-3">
                            <label class="col-sm-1 me-auto col-form-label" for="password">Password:</label>
                                <div class="col-sm-9 my-1">
                            <input class="col-sm-9" type="password" name="password" id="password">
                            </div>
                            <?php if ($passwordError) : ?>
                                <p class="error">Invalid Password</p>
                            <?php endif ?>
                        </div>
                        <button class="btn btn-outline-success my-1" type="submit">Login</button>
                        <p class="my-1 ms-2">No account? <a href="register.php">Register now!</a></p>
                        </form>
                        </div>
                </div>   
                <?php include('aside.php') ?> 
            </div>
        </div>  
    </div>   
</body>
</html>