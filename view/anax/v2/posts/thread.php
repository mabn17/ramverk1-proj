<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;
use Anax\MdFilter\MdFilter;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

$gravatar = new Gravatar;
$mdfilter = new MdFilter;
$displayedIds = [];
$userIsCreator = ($admin->id == $mainUser->id) ? true : false;

?><h1 class="mb-2"><?= $mainThread->title ?> <?= $postDb->isAnswerd($mainThread) ?></h1>
<!-- Tags för main post -->
<small>
    Taggar: 
    <?php $str = ""; foreach (explode(",", $mainThread->tagss) as $subTag) : ?>
        <?php $tagUrl = $postDb->getTagUrl($subTag, 1, $di) ?>
        <?php $subTag = "<a href=" . url($tagUrl) . ">$subTag</a>, "?>
        <?php $str .= $subTag ?>
    <?php endforeach; ?>
    <?= $str ?>
</small>

<!-- Innehåll för main post -->
<div class="p-3"><?= $mdfilter->parse($mainThread->data) ?></div>
<img src="<?= $gravatar->getGravatar($mainUser->email) ?>" alt="Gravatar" class="img-fluid img-thumbnail p-2 mb-3">
<small>Inskickad <?= $mainThread->created ?> av <?= $mainUser->username ?></small>
<?php $comments = $postDb->getAllComments($mainThread->id, $di) ?>

<!-- Kommentarer för main post -->
<h3>Kommentarer</h3>
Lägg till kommentar <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($mainThread->id, "comment"))) ?>
<?php if ($comments != null) { ?>
    <?php foreach ($postDb->getAllComments($mainThread->id, $di) as $comment) : ?>
        <?php $mainCommentUsers = $usr->getUserInfo("id", $comment->userId, $di) ?>
        <small><?= $mdfilter->parse($comment->data) ?></small>
        <small>Av: <?= $mainCommentUsers->username ?></small>
    <?php endforeach; ?>
<?php } ?>

<!-- Svar till main post -->
<h2>Svar</h2>
Svara <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($mainThread->id, "answer"))) ?>
<?php if ($answers != null) { ?>
    <?php foreach ($answers as $ans) : ?>
        <?php if (!in_array($ans->id, $displayedIds)) { ?>
            <?php $displayedIds[] = $ans->id ?>
            <div class="p-3"><?= $mdfilter->parse($ans->data) ?></div>
            <img src="<?= $gravatar->getGravatar($ans->email) ?>" alt="Gravatar" class="img-fluid img-thumbnail p-2 mb-3">
            <small>Inskickad <?= $ans->created ?> av <?= $ans->username ?></small><br><?= $postDb->isAnswerd($mainThread, 1) ?>

            <!-- Sätter en länk som markerar svaret till "Accepterat" -->
            <br><small><?= ($userIsCreator) ? $postDb->getMarkAsAnswerLink(url("post/post"), $mainThread->id, $ans->id) : null ?></small>

            <!-- Kommentarer för sub post -->
            <h3>Kommentarer</h3>
            Lägg till kommentar <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($ans->id, "comment"))) ?>
            <?php $subComments = $postDb->getAllComments($ans->id, $di); ?>
            <?php foreach ($subComments as $subC) : ?>
                <?php $commentUser = $usr->getUserInfo("id", $subC->userId, $di) ?>
                <small><?= $mdfilter->parse($subC->data) ?></small>
                <small>Av: <?= $commentUser->username ?></small>
            <?php endforeach; ?>
        <?php } ?>
    <?php endforeach; ?>
<?php } ?>
