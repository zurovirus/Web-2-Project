<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   

    //Requires these php files to be included.
    require('connect.php');

    // A query that selects a row from the table based on the id.   
    $query = "SELECT * FROM posts WHERE postId = :postId LIMIT 1";

    // Prepares the data for the query.
    $statement = $db->prepare($query);
    
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $id = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);

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
    <h1><a href="index.php">Party Finder</a></h1>
    <div id="nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="create.php">New Post</a></li>
        </ul>     
    </div>
    <div class="posts">
        <h2><?= $posts['title'] ?></h2>
        <p class="date">Date created: <?= date("F d, Y, g:i a", strtotime($posts['dateCreated'])) ?> - <a href="edit.php?id=<?= $posts['postId'] ?>">Edit</a></p>
        <p><?= $posts['content'] ?></p>
        <?php if ($posts['updated'] != null) : ?>
                <p class="date"> Date edited: <?= date("F d, Y, g:i a", strtotime($posts['updated'])) ?></p>
        <?php endif ?>
    </div>
</body>
</html> 