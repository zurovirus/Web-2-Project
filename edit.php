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

    $error = false;
    $errorMessages = [];

    if (empty($_POST['table']))
    {
        $table = $_POST['edit'] . "s";
        $tableid = $_POST['edit'] . "Id";
        // Checks if there is post data and if post title is empty and adds a message to the error messages.
        if ($_POST && empty($_POST['title']))
        {
            $error = true;
            $errorMessages[] .= "Title cannot be empty.";
        }

        // Checks if there is post data and if post content is empty and adds a message to the error messages.
        if ($_POST && empty($_POST['content']))
        {
            $error = true;
            $errorMessages[] .= "Content cannot be empty.";
        }

        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        if ($_POST)
        {
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);

            $content = $purifier->purify($_POST['content']);
            $title  = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id      = filter_input(INPUT_POST, $tableid, FILTER_SANITIZE_NUMBER_INT);
        }

        // Checks if post data exists for each input field and updates the data when the update button is clicked.
        if ($_POST && !empty($_POST['title']) && !empty($_POST['content']) && isset($_POST[$tableid]) && $_POST['submit'] == "update")
        {
            // Build the parameterized SQL query and bind to the above sanitized values.
            $query     = "UPDATE $table SET title = :title, content = :content WHERE $tableid = :$tableid LIMIT 1";

            // Prepares the data for the query.
            $statement = $db->prepare($query);

            // Binds the data to the values.
            $statement->bindValue(':title', $title);        
            $statement->bindValue(':content', $content);
            $statement->bindValue(":$tableid", $id, PDO::PARAM_INT);
            
            // Execute the INSERT.
            $statement->execute();
            
            // Redirect after update.
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

        // Checks if posts data exists and deletes the data if the delete button is clicked.
        elseif ($_POST && $_POST['submit'] == "delete")
        {
            // Build the parameterized SQL query and bind to the above sanitized values.
            $query     = "DELETE FROM $tables WHERE $tableid = :$tableid LIMIT 1";

            // Prepares the data for the query.
            $statement = $db->prepare($query);

            // Binds the data to the values.
            $statement->bindValue(":$tableid", $id, PDO::PARAM_INT);
            
            // Execute the DELETE.
            $statement->execute();
            
            // Redirect after update.
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
    }
    
    // Checks if the get is Set and fetches the data.
    elseif (isset($_GET))
    {
        $table = $_POST['table'] . "s";
        $tableid = $_POST['table'] . "Id";

        // Sanitizes the user input and filters out everything but INTs.
        $id = filter_input(INPUT_GET, $tableid, FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $query = "SELECT * FROM $table WHERE $tableid = :$tableid LIMIT 1";

        // Prepares the data for the query.
        $statement = $db->prepare($query);

        // Binds the data to the values.
        $statement->bindValue($tableid, $id, PDO::PARAM_INT);

        // Execute the SELECT.
        $statement->execute();
        
        // Retrieves the data row.
        $row = $statement->fetch();

        // If the sanitized ID does not equal the GET[id] or if the retrieved ID is empty. Redirects to index.php.
        if ($id != $_GET[$tableid] || $row[$tableid] == null) 
        {
            header("Location: index.php");
            exit;
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
    <title>Edit this Post!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
     <!-- If an error occurs, the error page displays, else the edit loads. -->
    <?php if ($error) : ?>
        <h1>An error has occurred.</h1>
        <?php foreach ($errorMessages as $errorMessage) : ?>
            <p><?= $errorMessage ?> </p>
        <?php endforeach ?>
        <a class="home" href="index.php">Return Home</a>
    <?php else : ?>
        <h1><a href="index.php">Party Finder</a></h1>
        <div id="nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="lfp.php">Looking for Party</a></li>
            </ul>     
        </div>
        <form method="post">
            <input type="hidden" name=<?= $tableid ?> value="<?= $row[$tableid] ?>">
            <input type="hidden" name="edit" value="<?= $_POST['table'] ?>">
            <label for="title">Title</label>
            </br>
            <input type="text" name="title" id="title" value="<?= $row['title'] ?>">
            </br>
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="60" rows="10"><?= $row['content'] ?></textarea>
            <script type="text/javascript">tinyMCE.init({
        selector : "#content"
    });
        </script>
            <span>
                <button type="submit" name="submit" value="update">Update</button>
                <button type="submit" name="submit" value="delete" onclick="confirm('Are you sure you want to delete?')">Delete</button>
            </span>
        </form>
    <?php endif ?>
</body>
</html>