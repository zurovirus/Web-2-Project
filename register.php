<?php
    $error = false;
    $errorMessages = [];
    if ($_POST)
    {
        $postsData = [$_POST['username'], $_POST['password'], $_POST['confirmpassword'], $_POST['name'], $_POST['email']];

        function emptyCheck($datas){
            foreach($datas as $data){
                if(empty($data))
                {
                    $error = true;
                    $errorMessages[] .= "A field cannot be empty."
                }
            }
        }

        emptyCheck($postsData);

        if ($_POST['password'] != $_POST['confirmpassword']){
            $error = true;
            $errorMessages[] .= "Passwords mismatched."
        }
        
    }

    if ($_POST && empty($_POST['username']))
    {
        $error = true;
        $errorMessages[] .= "Username cannot be empty.";
    }

    if ($_POST && empty($_POST['password']))
    {
        $error = true;
        $errorMessages[] .= "Password cannot be empty.";
    }

    if ($_POST && empty($_POST['confirmpassword']))
    {
        $error = true;
        $errorMessages[] .= "Confirm the password.";
    }

    if ($_POST && empty($_POST['name']))
    {
        $error = true;
        $errorMessages[] .= "Please enter your name.";
    }

    if ($_POST && empty($_POST['email']))
    {
        $error = true;
        $errorMessages[] .= "Please enter your email address.";
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