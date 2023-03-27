<?php
    require('connect.php');

    $selectQuery = "SELECT * FROM news INNER JOIN users ON users.userId = news.userId ORDER BY newId DESC LIMIT 5";

    // Prepares the data for the query.
    $statement = $db->prepare($selectQuery);

    // Execute the SELECT.
    $statement->execute();

?>

<aside class= "aside">
<?php while ($new = $statement->fetch()) : ?>
    <div class="news">
        <h2> <?= $new['title'] ?></h2>
        <p> <?= date("F d, Y, g:i a", strtotime($new['date'])) ?></p>
        <?php if (strlen($new['content']) > 100) : ?>
            <p> <?= substr($new['content'], 0, 100) . "..." ?><br><a href="post.php?id=<?= $new['id'] ?>">Read Full Post</a></p>
        <?php else : ?>
            <p> <?= $new['content'] ?></p>
        <?php endif ?>
    </div>
<?php endwhile ?>
</aside>
