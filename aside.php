<?php
    require('connect.php');

    $asideQuery = "SELECT * FROM news INNER JOIN users ON users.userId = news.userId ORDER BY newId DESC LIMIT 5";

    // Prepares the data for the query.
    $aside = $db->prepare($selectQuery);

    // Execute the SELECT.
    $aside->execute();

?>

<aside class= "aside">
<?php while ($news = $aside->fetch()) : ?>
    <div class="news">
        <h2> <?= $news['title'] ?></h2>
        <p> <?= date("F d, Y, g:i a", strtotime($news['date'])) ?></p>
        <?php if (strlen($news['content']) > 100) : ?>
            <p> <?= substr($news['content'], 0, 100) . "..." ?><br><a href="post.php?id=<?= $news['id'] ?>">Read Full Post</a></p>
        <?php else : ?>
            <p> <?= $news['content'] ?></p>
        <?php endif ?>
    </div>
<?php endwhile ?>
</aside>
