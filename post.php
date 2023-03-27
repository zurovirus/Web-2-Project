<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   

    //Requires these php files to be included.
    require('connect.php');

    session_start();

    // A query that selects a row from the table based on the id.   
    $query = "SELECT * FROM posts INNER JOIN users ON users.userId = posts.userId WHERE postId = :postId LIMIT 1";
    $editQuery = "SELECT * FROM users INNER JOIN posts ON users.userId = posts.userEditId WHERE postId = :postId LIMIT 1";

    $statement = $db->prepare($editQuery);
    $id = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);

    // Binds the data to the values.
    $statement->bindValue('postId', $id, PDO::PARAM_INT);

    $statement->execute();
    $editName = $statement->fetch();

    // Prepares the data for the query.
    $statement = $db->prepare($query);

    // Binds the data to the values.
    $statement->bindValue('postId', $id, PDO::PARAM_INT);

    // Execute the SELECT.
    $statement->execute();

    // Retrieves the data row.
    $posts = $statement->fetch();
    
    // If the id does not match the $_GET value or if the row is empty, returns the user to the index.
    if ($id != $_GET['postId'] || $posts['postId'] == null) 
    {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Post</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<?php include('header.php') ?>
    <div class="posts">
    <?php if (!empty($_POST)) : ?>
        <input type="hidden" name="edit" value="<?= $_POST['table'] ?>">
    <?php endif ?>
        <h2><?= $posts['title'] ?></h2>
        <p class="date">Date created: <?= date("F d, Y, g:i a", strtotime($posts['dateCreated'])) ?></p>
        <p>By: <a href="member.php?userId=<?= $posts['userId'] ?>"><?= $posts['userName'] ?></a></p>
        <p><?= $posts['content'] ?></p>
        <?php if ($posts['updated'] != null) : ?>
                <p class="date">Edited by: <a href="member.php?userId=<?= $editName['userEditId'] ?>"><?= $editName['userName'] ?></a> on <?= date("F d, Y, g:i a", strtotime($posts['updated'])) ?></p>
        <?php endif ?>
        <?php if (isset($_SESSION['userId'])) : ?>
                <?php if ($_SESSION['userId'] == $posts['userId'] || $_SESSION['authorization'] >= 3) : ?>
                    <form action="edit.php?postId=<?= $posts['postId'] ?>" method="post">
                    <button type="submit" name="table" value="post">Edit</button>
                    </form>     
                <?php endif ?>
            <?php endif ?>
    </div>
</body>
</html> 