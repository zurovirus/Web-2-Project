<?php
    // A select query based off the id in descending order up to 5 records.
    $categoryQuery = "SELECT * FROM category WHERE categoryId > 1 ORDER BY categoryName";

    // Prepares the data for the query.
    $categoryStatement = $db->prepare($categoryQuery);

    // Execute the SELECT.
    $categoryStatement->execute();

?>
<div class="jumbotron">
    <h1 class="display-4"><a href="index.php">Party Finder</a></h1>
</div>
    <nav id="nav" class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="lfp.php">Looking for Party</a></li>
            <?php if (isset($_SESSION['user'])) : ?>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <li class="nav-item"><a class="nav-link" href="members.php">Users</a></li>
            <?php else : ?>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <?php endif ?>
            <?php if (isset($_SESSION['authorization']) && $_SESSION['authorization'] >= 3) : ?>
                <li class="nav-item"><a class="nav-link" href="category.php">Category</a></li>
            <?php endif ?>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="lfp.php" method="post">
            <input class="form-control- mr-sm-2" type="text" name="search" id="search" placeholder="Search for a group">
            <select name="category" id="category">
            <option value="> 0">--</option>
            <?php while ($catSearch = $categoryStatement->fetch()) : ?>
                <option value="= <?= $catSearch['categoryId']?>"><?= $catSearch['categoryName'] ?></option>
            <?php endwhile ?>
            </select>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="find" value="find">Investigate</button>
        </form>
    </nav>