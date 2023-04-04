<?php
    require('connect.php');
    
    $error = false;
    $errorMessages = [];
    
    function emptyCheck($datas){
        foreach($datas as $data){
            $empty = false;

            if(empty($data)){
            
                $empty = true; 
                return $empty;                    
            }
        }
        
        return false;
    }

    if ($_POST){
        $postsData = [$_POST['username'], $_POST['password'], $_POST['confirmpassword'], $_POST['name'], $_POST['email']];

        $error = emptyCheck($postsData);

        if ($error){
            $errorMessages[] .= "A field cannot be empty.";
        }

        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $usernamecheck = "SELECT * FROM users WHERE userName = :userName LIMIT 1";

        $statement = $db->prepare($usernamecheck);
        $statement->bindValue(':userName', $username, PDO::PARAM_STR);
        $statement->execute();

        $fetchname = null;

        while($fetch = $statement->fetch()){
            $fetchname = $fetch['userName'];
        }
        
        if ($fetchname == $username && !empty($username)){
            $error = true;
            $errorMessages[] .= "Username is taken!";
        }

        if ($_POST['password'] != $_POST['confirmpassword']){
            $error = true;
            $errorMessages[] .= "Passwords mismatched.";
        }

        if(!$error){
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $fullName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $query = "INSERT INTO users (userName, password, fullName, email) VALUES (:username, :password, :fullName, :email)";

            $statement = $db->prepare($query);

            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $passwordhash);
            $statement->bindValue(':fullName', $fullName);
            $statement->bindValue(':email', $email);

            $statement->execute();

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
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Register</title>
</head>
<body class="bg-image" style="background-image: url('images/board.jpg');">
    <?php include('header.php') ?>
    <?php include('login.php') ?>
            <div class="col">
                <div class="bg-image" style="background-image: url('images/post.png');">
                    <h1 class="my-4 text-center fw-bolder">Register a New Account</h1>
                </div>
                <?php if ($error) : ?>
                    <h1 class="text-white">An error has occurred.</h1>
                    <?php foreach ($errorMessages as $errorMessage) : ?>
                        <p class="text-white"><?= $errorMessage ?></p>
                    <?php endforeach ?>
                    <a class="text-decoration-none" href="register.php">Back</a>
                <?php else : ?>
                <div class="container">
                    <form class="col-3 offset-md-5" action="register.php" method="post">
                        <div class="bg-image" style="background-image: url('images/Parchment.jpg');">
                            <label class="mx-4 my-2" for="username">Username: </label>
                            <input class="mx-4" type="text" name="username" id="username">
                            <label class="mx-4 my-2" for="password">Password: </label>
                            <input class="mx-4" type="password" name="password" id="password">
                            <label class="mx-4 my-2" for="confirmpassword">Confirm Password: </label>
                            <input class="mx-4" type="password" name="confirmpassword" id="confirmpassword">
                            <label class="mx-4 my-2" for="name">Name: </label>
                            <input class="mx-4"type="text" name="name" id="name">   
                            <label class="mx-4 my-2" for="password">Email: </label>
                            <input class="mx-4" type="email" name="email" id="email">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-success mt-4 mb-3" type="submit">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif ?>
            </div>   
            <?php include('aside.php') ?> 
         </div>
    </div>     
</body>
</html>