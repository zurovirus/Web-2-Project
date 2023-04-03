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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <title>Document</title>
</head>
<body>
    <?php include('header.php') ?>
    <?php include('login.php') ?>
            <div class="col">
                <?php if ($error) : ?>
                    <h1>An error has occurred.</h1>
                    <?php foreach ($errorMessages as $errorMessage) : ?>
                        <p><?= $errorMessage ?> </p>
                    <?php endforeach ?>
                    <a class="home" href="register.php">Back</a>
                <?php else : ?>
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
                    <button type="submit">Register</button>
                </form>
                <?php endif ?>
            </div>   
            <?php include('aside.php') ?> 
         </div>
    </div>     
</body>
</html>