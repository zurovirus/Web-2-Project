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

        $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $insertQuery = "INSERT INTO comments (userId, postId, content) VALUES (:userId, :postId, :content)";

        $insertStatement = $db->prepare($insertQuery);
        
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
    if ($id != $_GET['postId'] || $posts['postId'] == null) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Post</title>
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
</head>
<body class="bg-image" style="background-image: url('images/board.jpg');">
<?php include('header.php') ?>
<?php include('login.php') ?>
        <div class="col my-4">
            <?php if (!empty($_POST) && isset($_POST['table'])) : ?>
                <input type="hidden" name="edit" value="<?= $_POST['table'] ?>">
            <?php endif ?>
            <div class="bg-image" style="background-image: url('images/ParchmentCenter.png');">
                <div class="col ms-5 me-5">
                    <div class="row ms-5 me-5">
                        <div class="my-3">
                            <h2 class="text-center"><?= $posts['title'] ?></h2>
                            <p class="my-1">Date created: <?= date("F d, Y, g:i a", strtotime($posts['dateCreated'])) ?></p>
                            <p class="my-1"><img src="images/<?= $posts['thumbnail'] ?>" alt="thumbnail"> <a href="member.php?userId=<?= $posts['userId'] ?>" class="text-decoration-none"><?= $posts['userName'] ?></a></p>
                            <p class="my-2"><?= $posts['content'] ?></p>
                            <?php if ($posts['updated'] != null) : ?>
                                    <p class="my-2">Edited by: <a href="member.php?userId=<?= $editName['userEditId'] ?>" class="text-decoration-none">
                                    <?= $editName['userName'] ?></a> on <?= date("F d, Y, g:i a", strtotime($posts['updated'])) ?></p>
                            <?php endif ?>
                            <?php if (isset($_SESSION['userId'])) : ?>
                                <?php if ($_SESSION['userId'] == $posts['userId'] || $_SESSION['authorization'] >= 3) : ?>
                                    <form action="edit.php?postId=<?= $posts['postId'] ?>" method="post">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm" type="submit" name="table" value="post">Edit</button>
                                    </div>
                                    </form>     
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-6 ">
                <?php if ($rowcount != 0) : ?>
                    <h4 class="text-white mt-2">Comments</h4>
                    <?php while ($comment = $commentStatement->fetch()) : ?>
                        <div class="my-3">
                        <div class="bg-image" style="background-image: url('images/Parchment.jpg');">
                        <div class="row">
                        <?php if ($comment['userId'] == NULL) : ?>
                            <p class="fst-italic pt-2 ms-2"><img src="images/unidentified_thumbnail.jpg" alt="thumbnail"> anonymous</p>
                        <?php else : ?>
                            <p class="pt-2 ms-2"><img src="images/<?= $comment['thumbnail'] ?>" alt="thumbnail"> 
                            <a href="member.php?userId=<?= $comment['userId'] ?>" class="text-decoration-none"><?= $comment['userName'] ?></a></p>
                        <?php endif ?>
                        </div>
                        <p class="ms-3">Date posted: <?= date("F d, Y, g:i a", strtotime($comment['date'])) ?></p>
                        <p class="lh-sm pb-3 ms-4"><?= $comment['content'] ?></p>
                        <?php if (isset($_SESSION['authorization'])) : ?>
                            <?php if ($_SESSION['userId'] == $comment['userId'] || $_SESSION['authorization'] >= 3) : ?>
                                <form action="post.php?postId=<?= $posts['postId'] ?>" method="post">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-outline-danger btn-sm mb-2 mx-2" type="submit" name="delete" value=<?= $comment['commentId'] ?> 
                                            onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                                    </div>
                                </form>  
                            <?php endif ?>
                        <?php endif ?>
                        </div>
                        </div>
                    <?php endwhile ?>
                <?php endif ?>
            </div>
            <form action="post.php?postId=<?= $posts['postId'] ?>" method="post">
                <label for="comment" class="d-flex my-2 text-white">Add a comment</label>
                <textarea name="comment" id="comment" cols="57" rows="5"></textarea>
                <div class="col-6 my-2">
                    <button class="btn btn-outline-success my-2" type="submit" name="button" value="comment">Submit</button>
                </div>
            </form>
        </div>
        <?php include('aside.php') ?>
    </div>
    </div>
    
    </div>
</div>
</body>
</html> 