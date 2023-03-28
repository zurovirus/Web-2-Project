<?php

    $asideQuery = "SELECT * FROM posts ORDER BY postId DESC LIMIT 5";

    // Prepares the data for the query.
    $asides = $db->prepare($asideQuery);

    // Execute the SELECT.
    $asides->execute();

?>

<aside class= "aside">
<?php while ($aside = $asides->fetch()) : ?>
    <div class="news">
        <h2> <?= $aside['title'] ?></h2>
        <?php if (strlen($aside['content']) > 100) : ?>
            <p> <?= substr($aside['content'], 0, 100) . "..." ?><br><a href="post.php?postId=<?= $aside['postId'] ?>">Investigate further...</a></p>
        <?php else : ?>
            <p> <?= $aside['content'] ?></p>
        <?php endif ?>
    </div>
<?php endwhile ?>
</aside>
