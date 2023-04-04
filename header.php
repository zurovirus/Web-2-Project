<?php
    // A select query based off the id in descending order up to 5 records.
    $categoryQuery = "SELECT * FROM category WHERE categoryId > 1 ORDER BY categoryName";
    $lfpQuery = "SELECT * FROM category";

    // Prepares the data for the query.
    $categoryStatement = $db->prepare($categoryQuery);
    $lfpStatement = $db->prepare($lfpQuery);

    // Execute the SELECT.
    $categoryStatement->execute();
    $lfpStatement->execute();

?>
<div class="bg-image w-100"  style="background-image: url('images/banner.png');">
<div class="form-outline">
<form class="input-group w-25 ms-auto" action="lfp.php" method="post">
    <input class="form-control me-2" type="search" name="search" id="search" placeholder="Search for a group" aria-label="Search">
    <select class="form-select" name="category" id="category">
    <option value="> 0">All</option>
    <?php while ($catSearch = $categoryStatement->fetch()) : ?>
        <option value="= <?= $catSearch['categoryId']?>"><?= $catSearch['categoryName'] ?></option>
    <?php endwhile ?>
    </select>
    <button class="btn btn-dark mx-2 my-2 my-sm-0" type="submit" name="find" value="find">Investigate</button>
</form>
</div>
<div class="jumbotron">
    <div class="my-5 "></div>
</div>
</div>
<nav id="nav" class="navbar navbar-expand navbar-dark bg-dark">
    <ul class="navbar-nav">
        <li class="nav-item active mx-4"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item dropdown mx-4">
        <a class="nav-link dropdown" href="lfp.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Looking for Party
        </a>
        <div class="dropdown-menu dropdown-menu-dark text-center" aria-labelledby="navbarDropdown">
            <?php while ($lfpCategory = $lfpStatement->fetch()) : ?>
                <a class="dropdown-item" href="lfp.php?categoryId=<?= $lfpCategory['categoryId'] ?>"><?= $lfpCategory['categoryName'] ?></a>
            <?php endwhile ?>
        </div>
        </li>
        <?php if (isset($_SESSION['user'])) : ?>
            <li class="nav-item mx-4"><a class="nav-link" href="members.php">Users</a></li>
            <?php if ($_SESSION['authorization'] >= 3) : ?>
                <li class="nav-item mx-4"><a class="nav-link" href="category.php">Category</a></li>
            <?php endif ?>
            <li class="nav-item mx-4"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else : ?>
            <li class="nav-item mx-4"><a class="nav-link" href="loginpage.php">Login</a></li>
        <?php endif ?>
    </ul>
</nav>
