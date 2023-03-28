<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   

    //Requires these php files to be included.
    require('connect.php');

    session_start();

    $id = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);

    if (isset($_POST['delete'])){
        $delete = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT);

        $deleteQuery = "DELETE FROM comments WHERE commentId = $delete LIMIT 1";
        $deleteStatement = $db->prepare($deleteQuery);
        $deleteStatement->execute();  

        header("Location: post.php?postId=$id");
        exit;
    }
    
    if (!empty($_POST['button'])) {
        $insertQuery = "INSERT INTO comments (userId, postId, content) VALUES (:userId, :postId, :content)";

        $insertStatement = $db->prepare($insertQuery);

        $content = $_POST['comment'];

        $insertStatement->bindValue(':postId', $id);
        $insertStatement->bindValue(':content', $content);

        if (isset($_SESSION['userId'])){
            $userId = filter_var($_SESSION['userId'], FILTER_SANITIZE_NUMBER_INT);

            $insertStatement->bindValue(':userId', $userId);
        }
        else{
            $insertStatement->bindValue(':userId', NULL);
        }

        $insertStatement->execute();

        header("Location: post.php?postId=$id");
        exit;
    }

    $commentQuery = "SELECT * FROM comments LEFT OUTER JOIN users ON users.userId = comments.userId WHERE postId = $id ORDER BY commentId DESC";
        
    $commentStatement = $db->prepare($commentQuery);

    $commentStatement->execute();

    $rowcount = $commentStatement->rowCount();

    // A query that selects a row from the table based on the id.   
    $query = "SELECT * FROM posts INNER JOIN users ON users.userId = posts.userId WHERE postId = :postId LIMIT 1";
    $editQuery = "SELECT * FROM users INNER JOIN posts ON users.userId = posts.userEditId WHERE postId = :postId LIMIT 1";

    $statement = $db->prepare($editQuery);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
<?php include('header.php') ?>
<div class="container">
    <div class="row">
        <div class="col" id="content">
            <?php if (!empty($_POST) && isset($_POST['table'])) : ?>
                <input type="hidden" name="edit" value="<?= $_POST['table'] ?>">
            <?php endif ?>
            <div class="my-4">
                <h2><?= $posts['title'] ?></h2>
                <p class="date">Date created: <?= date("F d, Y, g:i a", strtotime($posts['dateCreated'])) ?></p>
                <p>By: <a href="member.php?userId=<?= $posts['userId'] ?>"><?= $posts['userName'] ?></a></p>
                <p><?= $posts['content'] ?></p>
                <?php if ($posts['updated'] != null) : ?>
                        <p class="date">Edited by: <a href="member.php?userId=<?= $editName['userEditId'] ?>">
                        <?= $editName['userName'] ?></a> on <?= date("F d, Y, g:i a", strtotime($posts['updated'])) ?></p>
                <?php endif ?>
                <?php if (isset($_SESSION['userId'])) : ?>
                    <?php if ($_SESSION['userId'] == $posts['userId'] || $_SESSION['authorization'] >= 3) : ?>
                        <form action="edit.php?postId=<?= $posts['postId'] ?>" method="post">
                        <button type="submit" name="table" value="post">Edit</button>
                        </form>     
                    <?php endif ?>
                <?php endif ?>
                <?php if ($rowcount != 0) : ?>
                    <h4>Comments</h4>
                    <?php while ($comment = $commentStatement->fetch()) : ?>
                        <?php if ($comment['userId'] == NULL) : ?>
                            <p class="fst-italic my-0 ms-2">anonymous</p>
                        <?php else : ?>
                            <p class="my-0 ms-2">By: <a href="member.php?userId=<?= $comment['userId'] ?>"><?= $comment['userName'] ?></a></p>
                        <?php endif ?>
                        <p class="my-0 ms-4">Date posted: <?= date("F d, Y, g:i a", strtotime($comment['date'])) ?></p>
                        <p class="lh-sm my-2 ms-4"><?= $comment['content'] ?></p>
                        <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                            <form action="post.php?postId=<?= $posts['postId'] ?>" method="post">
                            <button class="btn btn-outline-success mx-auto my-2" type="submit" name="delete" value=<?= $comment['commentId'] ?> 
                            onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                            </form>  
                        <?php endif ?>
                    <?php endwhile ?>
                <?php endif ?>
            </div>
            <form action="post.php?postId=<?= $posts['postId'] ?>" method="post">
                <label for="comment" class="my-2">Add a comment</label>
                    </br>
                <textarea name="comment" id="comment" cols="50" rows="10"></textarea>
                    </br>
                <button class="btn btn-outline-success mx-auto my-2" type="submit" name="button" value="comment">Submit</button>
            </form>
        </div>
        <?php include('aside.php') ?>
    </div>
</div>
</body>
</html> 