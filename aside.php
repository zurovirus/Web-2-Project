<?php

    $asideQuery = "SELECT * FROM posts ORDER BY postId DESC LIMIT 5";

    // Prepares the data for the query.
    $asides = $db->prepare($asideQuery);

    // Execute the SELECT.
    $asides->execute();

?>
<div class="col-sm-2">
<aside class= "container">
    <ul class="list-group" style="width: 300px";>
        <?php while ($aside = $asides->fetch()) : ?>
            <li class="list-group-item justfiy-content-between align-items-center">
                <h4> <a href="post.php?postId= <?= $aside['postId'] ?>"><?= $aside['title'] ?></a></h3>
                <?php if (strlen($aside['content']) > 50) : ?>
                    <p> <?= substr($aside['content'], 0, 50) . "..." ?><br><a href="post.php?postId=<?= $aside['postId'] ?>">Investigate further...</a></p>
                <?php else : ?>
                    <p> <?= $aside['content'] ?></p>
                <?php endif ?>
            </li>
        <?php endwhile ?>
    </ul>
</aside>
</div>
