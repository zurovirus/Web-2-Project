<h1><a href="index.php">Party Finder</a></h1>
    <div id="nav">
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