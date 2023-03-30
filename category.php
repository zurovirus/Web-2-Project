<?php
    require('connect.php');

    session_start();

    if ($_POST)
    {
        $filteredCategory  = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $filteredId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($_POST['post'] == "add")
        {
            $query = "INSERT INTO category (categoryName) VALUES (:categoryName)";
    
            $statement = $db->prepare($query);
                
            $statement->bindValue(":categoryName", $filteredCategory);
            
            // Execute the INSERT.
            $statement->execute();
    
            header("Location: category.php");
            exit;
        }

        if ($_POST['post'] == "delete")
        {
            $query = "DELETE FROM category WHERE categoryId = :categoryId LIMIT 1";

            $statement = $db->prepare($query);
                
            $statement->bindValue(":categoryId", $filteredId, PDO::PARAM_INT);
            
            // Execute the INSERT.
            $statement->execute();
    
            header("Location: category.php");
            exit;
        }

        if ($_POST['post'] == "update")
        {
            $query = "UPDATE category SET categoryName = :categoryName WHERE categoryId = :categoryId";
            
            $statement = $db->prepare($query);
                
            $statement->bindValue(":categoryId", $filteredId, PDO::PARAM_INT);
            $statement->bindValue(":categoryName", $filteredCategory);

            $statement->execute();
    
            header("Location: category.php");
            exit;
        }
    }
    
    // A select query based off the id in descending order up to 5 records.
    $selectQuery = "SELECT * FROM category WHERE categoryId > 1 ORDER BY categoryName ASC";

    // Prepares the data for the query.
    $statement = $db->prepare($selectQuery);

    // Execute the SELECT.
    $statement->execute();
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
                <h2>Categories List</h2>
                <?php while ($category = $statement->fetch()) : ?>
                    <div class="categories">
                        <form action="category.php" method="post">
                            <?php if ($_POST && $_POST['post'] == 'edit ' . $category['categoryId']) : ?>
                                <input type="hidden" name="id" value="<?= $category['categoryId'] ?>">
                                <input type="text" name="category" value=<?= $category['categoryName'] ?> autofocus  onfocus="this.select()">
                                <button type="submit" name="post" value="update">Update</button>
                            <?php else : ?>
                                <input type="hidden" name="id" value="<?= $category['categoryId'] ?>">
                                <label><?= $category['categoryName'] ?></label>
                                <button type="submit" name="post" value="edit <?= $category['categoryId'] ?>">Edit</button>
                                <button type="submit" name="post" value="delete" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                            <?php endif ?>
                        </form>
                    </div>
                <?php endwhile ?>
                <form action="category.php" method="post">
                    <label for="category">Add a category</label>
                    <input type="text" name="category" id="category">
                    <button type="submit" name="post" value="add">Add</button>
                </form>
            </div>   
            <?php include('aside.php') ?> 
         </div>
    </div>     
</body>
</html>