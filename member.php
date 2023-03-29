<?php
    //Requires these php files to be included.
    require('connect.php');

    session_start();

    // A query that selects a row from the table based on the id.   
    $query = "SELECT * FROM users WHERE userId = :userId LIMIT 1";

    // Prepares the data for the query.
    $statement = $db->prepare($query);
    
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $id = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_NUMBER_INT);

    // Binds the data to the values.
    $statement->bindValue('userId', $id, PDO::PARAM_INT);

    // Execute the SELECT.
    $statement->execute();
        
    // Retrieves the data row.
    $user = $statement->fetch();
    
    // If the id does not match the $_GET value or if the row is empty, returns the user to the index.
    if ($id != $_GET['userId'] || $user['userId'] == null) 
    {
        header("Location: members.php");
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
                <div class="user">
                    <h2><?= $user['userName'] ?></h2>
                    <p><?= $user['fullName'] ?></p>
                    <p><?= $user['email'] ?></p>
                </div>
            </div>   
        <?php include('aside.php') ?> 
        </div>
    </div>     
</body>
</html> 