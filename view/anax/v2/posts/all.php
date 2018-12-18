<?php

namespace Anax\View;

/**
 * Displays all posts.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

/* Svaren på en fråga kan sorteras och visas antingen enligt
                        datum, eller rank (antalet röster). */
// Lös detta med om ?sort=votes ->
//     gör en array av dessa värden och sortera dem i controllern.

?><h1>Frågor</h1>
Skara ny fråga <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl(1, "post"))) ?>
<?php foreach ($questions as $question) : ?>
    <?php $questUsr = $usr->getUserInfo("id", $question->userId, $di) ?>

    <h2><a href="<?= url($usr->getPostUrl($question)) ?>"> <?= $question->title ?></a> <?= $postDb->isAnswerd($question) ?></h2>
    <small>Antal Svar: <?= count($postDb->getPostInfo("id", $question->id, $di)["sub"]) ?> <?= $postDb->getLikes($question->id, "post", $di, url(""))?></small><br>
    <small>Av: <a href="<?= url("users/user/$questUsr->id") ?>"><?= $questUsr->username ?></a> - <?= $question->created ?></small>
<?php endforeach; ?>
