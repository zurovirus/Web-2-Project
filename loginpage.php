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
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Document</title>
    <?php if (isset($_SESSION['user'])) : ?>
    <script type="text/javascript"> window.onload = function () { alert("Success!"); } </script>
    <?php endif ?>
</head>
<body class="bg-image" style="background-image: url('images/board.jpg');">
    <?php include('header.php') ?>
    <?php include('login.php') ?>
                <div class="col my-4">  
                    <div class="col-sm-4 me-4">
                        <div class="bg-image" style="background-image: url('images/loginParchment.png');">
                            <form class="" action="loginpage.php" method="post">
                                <div class="row mb-1 pt-3 ps-4">
                                    <label class="col-sm-1 me-auto mx-2 my-2 col-form-label" for="username">Username:</label>
                                    <div class="col-sm-9 my-3">
                                        <input class="col-sm-9" type="text" name="userName" id="userName">
                                    </div>
                                    <?php if ($userError) : ?>
                                        <p class="text-danger">Invalid Username</p>
                                    <?php endif ?>
                                </div>
                                <div class="row mb-1 ps-4">
                                    <label class="col-sm-1 me-auto mx-2 col-form-label" for="password">Password:</label>
                                        <div class="col-sm-9 my-1">
                                    <input class="col-sm-9" type="password" name="password" id="password">
                                        </div>
                                <?php if ($passwordError) : ?>
                                    <p class="text-danger">Invalid Password</p>
                                <?php endif ?>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-outline-success my-1 mx-3" type="submit">Login</button>
                                </div>
                                <p class="my-3 pb-3 ms-2 text-center">No account? <a class="text-decoration-none" href="register.php">Register now!</a></p>
                            </form>
                        </div>
                    </div>
                </div>   
                <?php include('aside.php') ?> 
            </div>
        </div>  
    </div>   
</body>
</html>