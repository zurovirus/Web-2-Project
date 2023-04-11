<!DOCTYPE html>
<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   

    //Requires these php files to be included.
    require('connect.php');
    require_once('htmlpurifier-4.15.0/library/HTMLPurifier.auto.php');

    session_start();

    $error = false;
    $errorMessages = [];

    if (empty($_POST['table'])){
        if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
            $table = $_POST['edit'] . "s";

            // Sanitize user input to escape HTML entities and filter out dangerous characters.
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);

            $content = $purifier->purify($_POST['content']);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $username = $_SESSION['user'];

            $select = "SELECT * FROM users WHERE userName = :userName LIMIT 1";
    
            $statement = $db->prepare($select);
            
            $statement->bindValue(":userName", $username);
            
            $statement->execute();

            $row = $statement->fetch();

            if (empty($_POST['category'])){
                $query = "INSERT INTO $table (title, content, userId) VALUES (:title, :content, :userId)";
                
                $statement = $db->prepare($query);
                
                $statement->bindValue(":title", $title);
                $statement->bindValue(":content", $content);
                $statement->bindValue(":userId", $row['userId']);
                
                $statement->execute();
            }
            else{
                $query = "INSERT INTO $table (title, content, userId, categoryId) VALUES (:title, :content, :userId, :categoryId)";
                
                $statement = $db->prepare($query);
                
                $statement->bindValue(":title", $title);
                $statement->bindValue(":content", $content);
                $statement->bindValue(":userId", $row['userId']);
                $statement->bindValue(":categoryId", $_POST['category']);
                
                $statement->execute();
            }

            switch ($table){
                case "news":
                    header("Location: index.php");
                    exit;
                    break;
                case "posts":
                    header("Location: lfp.php");
                    exit;
                    break;
            }
        }
    
        if ($_POST && empty($_POST['title'])){
            $error = true;
            $errorMessages[] .= "Title cannot be empty.";
        }
    
        if ($_POST && empty($_POST['content'])){
            $error = true;
            $errorMessages[] .= "Content cannot be empty.";
        }
    }

    $selectQuery = "SELECT * FROM category WHERE categoryId > 1 ORDER BY categoryId ASC";

    $statement = $db->prepare($selectQuery);

    $statement->execute();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="main.css">
    <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
    <title>My Blog Post!</title>
</head>
<body class="bg-image" style="background-image: url('images/board.jpg');"> 
    <?php include('header.php') ?>
    <?php include('login.php') ?>
            <div class="col">
                <?php if ($error) : ?>
                    <h1>An error has occurred.</h1>    
                    <?php foreach ($errorMessages as $errorMessage) : ?>
                        <p class="text-white"><?= $errorMessage ?> </p>
                    <?php endforeach ?>
                    <a class="text-decoration-none" href="index.php">Return Home</a>
                <?php else : ?>
                    <form action="create.php" method="post">
                        <label for="title" class="text-white mt-4 mb-2">Title</label>
                        </br>  
                        <input type="text" name="title" id="title">
                        </br> 
                        <label for="content" class="text-white my-2">Content</label>
                        <textarea name="content" id="content" cols="60" rows="10"></textarea>
                        <script type="text/javascript">tinyMCE.init({
                            selector : "#content"
                            });
                        </script>
                        <?php if ($_POST['table'] == 'post') : ?>
                            <label for="category" class="text-white mt-2 mx-3">Category</label>
                            <select name="category" id="category">
                                <option value="">None</option>
                                <?php while ($category = $statement->fetch()) : ?>
                                    <option value="<?= $category['categoryId']?>"><?= $category['categoryName'] ?></option>
                                <?php endwhile ?>
                            </select>     
                        <?php endif ?>
                        <span class="d-flex justify-content-end">
                            <button class="btn btn-success my-2 mx-1" type="submit" name="edit" value="<?= $_POST['table'] ?>">Post</button>
                        </span>
                    </form>
                <?php endif ?>
            </div>   
        <?php include('aside.php') ?> 
        </div>
    </div>     
</body>
</html>