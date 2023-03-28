<?php
    // A select query based off the id in descending order up to 5 records.
    $categoryQuery = "SELECT * FROM category WHERE categoryId > 1 ORDER BY categoryName";

    // Prepares the data for the query.
    $categoryStatement = $db->prepare($categoryQuery);

    // Execute the SELECT.
    $categoryStatement->execute();

?>

<h1><a href="index.php">Party Finder</a></h1>
    <div id="nav">
        <form action="lfp.php" method="post">
            <input type="text" name="search" id="search" placeholder="Search for a group">
            <select name="category" id="category">
            <option value="> 0">--</option>
            <?php while ($catSearch = $categoryStatement->fetch()) : ?>
                <option value="= <?= $catSearch['categoryId']?>"><?= $catSearch['categoryName'] ?></option>
            <?php endwhile ?>
            </select>
            <button type="submit" name="find" value="find">Investigate</button>
        </form>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="lfp.php">Looking for Party</a></li>
            <?php if (isset($_SESSION['user'])) : ?>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="members.php">Users</a></li>
            <?php else : ?>
                <li><a href="login.php">Login</a></li>
            <?php endif ?>
            <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                <li><a href="category.php">Category</a></li>
            <?php endif ?>
        </ul>
    </div>