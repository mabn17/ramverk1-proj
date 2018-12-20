<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;
use Anax\MdFilter\MdFilter;

/**
 * View to create a new book.
 */

$gravatar = new Gravatar;
$mdfilter = new MdFilter;
$displayedIds = [];
$userIsCreator = ($admin->id == $mainUser->id) ? true : false;

?><script>
function rateMe(action, type, id) {
    $.ajax({
        type: "GET",
        url: "../../vote/" + action + "/" + type + "/" + id,
        data: 'action=' + action + '&type=comment&id=' + id,
        success: function() {
            $.ajax({
                type: "GET",
                url: "../../api/get/" + type + "/" + id,
                data: 'type=' + type + "&id=" + id,
                success: function(response) {
                    var newPoints = response["points"];
                    var myId = '#' + type + id;
                    
                    $(myId).text("Poäng: " + newPoints);
                }
            });
        }
    });
};
</script>

<h1 class="mb-2"><?= $mainThread->title ?> <?= $postDb->isAnswerd($mainThread) ?></h1>
<!-- Tags för main post -->
<p>
    Sortera efter <a href="<?= url("post/post/$mainThread->id?sorted=true") ?>">poäng</a> eller
    <a href="<?= url("post/post/$mainThread->id") ?>">nyast sist</a>. 
</p>
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
<small>Inskickad <?= $mainThread->created ?> av <a href="<?= url("users/user/{$mainUser->id}")?>"><?= $mainUser->username ?></a></small>
<small><?= $postDb->getLikes($mainThread->id, "post", $di) ?></small>
<?php $comments = $postDb->getAllComments($mainThread->id, $di) ?>

<!-- Kommentarer för main post -->
<h3>Kommentarer</h3>
Lägg till kommentar <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($mainThread->id, "comment"))) ?>
<?php if ($comments != null) { ?>
    <?php foreach ($postDb->getAllComments($mainThread->id, $di) as $comment) : ?>
        <?php $mainCommentUsers = $usr->getUserInfo("id", $comment->userId, $di) ?>
        <small><?= $mdfilter->parse($comment->data) ?></small>
        <small>Av: <a href="<?= url("users/user/{$mainCommentUsers->id}")?>"><?= $mainCommentUsers->username ?></a></small>
        <small><?= $postDb->getLikes($comment->id, "comment", $di) ?></small>
        <br>--------------------------------------------------------------------------
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
            <small>Inskickad <?= $ans->created ?> av <a href="<?= url("users/user/{$ans->userId}")?>"><?= $ans->username ?></a></small><br><?= $postDb->isAnswerd($ans, 1) ?>

            <!-- Sätter en länk som markerar svaret till "Accepterat" -->
            <br><small><?= ($userIsCreator) ? $postDb->getMarkAsAnswerLink(url("post/post"), $mainThread->id, $ans->id) : null ?></small>
            <small><?= $postDb->getLikes($ans->id, "post", $di) ?></small>

            <!-- Kommentarer för sub post -->
            <h3>Kommentarer</h3>
            Lägg till kommentar <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($ans->id, "comment"))) ?>
            <?php $subComments = $postDb->getAllComments($ans->id, $di); ?>
            <?php foreach ($subComments as $subC) : ?>
                <?php $commentUser = $usr->getUserInfo("id", $subC->userId, $di) ?>
                <small><?= $mdfilter->parse($subC->data) ?></small>
                <small>Av: <a href="<?= url("users/user/{$commentUser->id}")?>"><?= $commentUser->username ?></a></small>
                <small><?= $postDb->getLikes($ans->id, "comment", $di) ?></small>
                <br>-------------------------------------------------------------------------
            <?php endforeach; ?>
        <?php } ?>
    <?php endforeach; ?>
<?php } ?>
