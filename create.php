<!DOCTYPE html>
<?php

/*******w******** 
    
    Name: To Phuc
    Date: 03/15/2023
    Description: Project

****************/   

    //Requires these php files to be included.
    require('connect.php');
    require('authenticate.php');    
    require_once('htmlpurifier-4.15.0/library/HTMLPurifier.auto.php');

    session_start();

    $error = false;
    $errorMessages = [];

    if (empty($_POST['table']))
    {
        if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) 
        {
            $table = $_POST['edit'] . "s";

            // Sanitize user input to escape HTML entities and filter out dangerous characters.
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);

            $content = $purifier->purify($_POST['content']);
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // A query that will insert the title and content into the table.
            $query = "INSERT INTO $table (title, content) VALUES (:title, :content)";
    
            // Prepares the data for the query.
            $statement = $db->prepare($query);
            
            // Binds the data to the values.
            $statement->bindValue(":title", $title);
            $statement->bindValue(":content", $content);
            
            // Execute the INSERT.
            $statement->execute();
    
            // Redirects the user to index.php after inserting into the table.
            switch ($table){
                case "pages":
                    header("Location: index.php");
                    exit;
                    break;
                case "posts":
                    header("Location: lfp.php");
                    exit;
                    break;
            }
        }
    
        // If there is post data and the title input is empty, an error is raised and a message added.
        if ($_POST && empty($_POST['title']))
        {
            $error = true;
            $errorMessages[] .= "Title cannot be empty.";
        }
    
        // If there is post data and the content input is empty, an error is raised and a message added.
        if ($_POST && empty($_POST['content']))
        {
            $error = true;
            $errorMessages[] .= "Content cannot be empty.";
        }
    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
    <title>My Blog Post!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <!-- If an error occurs, the error page will be displayed, else the form displays. -->
    <div id="nav">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="lfp.php">Looking for Party</a></li>
            <?php if (isset($_SESSION)) : ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else : ?>
                <li><a href="login.php">Login</a></li>
            <?php endif ?>
        </ul>     
    </div>
    <?php if ($error) : ?>
        <h1>An error has occurred.</h1>    
        <?php foreach ($errorMessages as $errorMessage) : ?>
            <p><?= $errorMessage ?> </p>
        <?php endforeach ?>
        <a class="home" href="index.php">Return Home</a>
    <?php else : ?>
    <h1><a href="index.php">Party Finder</a></h1>
        <form action="create.php" method="post">
            <label for="title">Title</label>
            </br>  
            <input type="text" name="title" id="title">
            </br> 
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="60" rows="10"></textarea>
            <script type="text/javascript">tinyMCE.init({
                selector : "#content"
                });
            </script>
            <span>
                <button type="submit" name="edit" value="<?= $_POST['table'] ?>">Post</button>
            </span>
        </form>
    <?php endif ?>
</body>
</html>