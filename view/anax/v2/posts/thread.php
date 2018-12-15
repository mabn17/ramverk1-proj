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

?><h1 class="mb-2"><?= $mainThread->title ?></h1>
<small>Taggar: <?= $mainThread->tagss ?></small>
<div class="p-3"><?= $mdfilter->parse($mainThread->data) ?></div>
<img src="<?= $gravatar->getGravatar($mainUser->email) ?>" alt="Gravatar" class="img-fluid img-thumbnail p-2 mb-3">
<small>Inskickad <?= $mainThread->created ?> av <?= $mainUser->username ?></small>
<hr class="">
<?php $comments = $postDb->getAllComments($mainThread->id, $di) ?>

<?php if ($comments != null) { ?>
    <h3>Kommentarer</h3>
    <?php foreach ($postDb->getAllComments($mainThread->id, $di) as $comment) : ?>
        <?php $mainCommentUsers = $usr->getUserInfo("id", $comment->userId, $di) ?>
        <small><?= $mdfilter->parse($comment->data) ?></small>
        <small>Av: <?= $mainCommentUsers->username ?></small>
        <hr class="clearfix">
    <?php endforeach; ?>
<?php } ?>

<?php if ($answers != null) { ?>
    <h2>Svar</h2>
    <?php foreach ($answers as $ans) : ?>
        <div class="p-3"><?= $mdfilter->parse($ans->data) ?></div>
        <img src="<?= $gravatar->getGravatar($ans->email) ?>" alt="Gravatar" class="img-fluid img-thumbnail p-2 mb-3">
        <small>Inskickad <?= $ans->created ?> av <?= $ans->username ?></small>
        <?php if ($ans->cdata != null) { ?>
            <h3>Kommentarer</h3>
            <?php foreach ($answers as $ansTwo) : ?>
                <?php if ($ansTwo->id == $ansTwo->cpost) { ?>
                    <?php $commentUser = $usr->getUserInfo("id", $ansTwo->cuser, $di) ?>

                    <small><?= $mdfilter->parse($ansTwo->cdata) ?></small>
                    <small>Av: <?= $commentUser->username ?></small>
                    <hr class="clearfix">
                <?php } ?>
                <hr>
            <?php endforeach; ?>
        <?php } ?>
    <?php endforeach; ?>
<?php }
