<?php

    $asideQuery = "SELECT * FROM posts ORDER BY postId DESC LIMIT 5";

    // Prepares the data for the query.
    $asides = $db->prepare($asideQuery);

    // Execute the SELECT.
    $asides->execute();

?>
<div class="col-3">
<aside class= "container"> 
    <div class="bg-image" style="background-image: url('images/tag.png');">
        <h3 class="text-center fw-bold text-white mt-4 mb-0">Adventurers In Need</h3>
    </div>
        <?php while ($aside = $asides->fetch()) : ?>
            <div class="bg-image" style="background-image: url('images/asideParchment.png');">
                <div class="col mx-4 mb-2">
                    <h4 class="text-center"><a class="text-decoration-none" href="post.php?postId=<?= $aside['postId'] ?>"><?= $aside['title'] ?></a></h4>
                    <?php if (strlen($aside['content']) > 50) : ?>
                        <p class="my-3"> <?= substr($aside['content'], 0, 50) . "..." ?></p>
                        <p class="my-2 text-end"><a class="text-decoration-none" href="post.php?postId=<?= $aside['postId'] ?>">Investigate further...</a></p>
                    <?php else : ?>
                        <p class="my-2"><?= $aside['content'] ?></p>
                        </br>
                    <?php endif ?>
                </div>
            </div>
        <?php endwhile ?>
    </div>
</aside>
</div>
</div>
