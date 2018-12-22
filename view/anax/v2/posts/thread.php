<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;
use Anax\MdFilter\MdFilter;

/**
 * View to create a new book.
 */

$gravatar = new Gravatar;
$mdfilter = new MdFilter;
$displayedIdsT = [];
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
<small class="float-right">
    <i class="fa fa-tags"></i> Taggar:
    <?php foreach (explode(",", $mainThread->tagss) as $subTag) : ?>
        <a href="<?= url($postDb->getTagUrl($subTag, 1, $di)) ?>" class="no-dec">
            <span class="badge badge-info"><?= $subTag ?></span>
        </a>
    <?php endforeach; ?>
</small>
<span class="float-left">
    Sortera svar efter <a href="<?= url("post/post/$mainThread->id?sorted=true") ?>">poäng</a> eller
    <a href="<?= url("post/post/$mainThread->id") ?>">nyast sist</a>. 
</span>
<div class="clearfix pb-3"></div>
<div class="container">
    <div class="row">
        <div class="media comment-box mb-4">
            <div class="media-left">
                <a href="<?= url("users/user/{$mainUser->id}")?>">
                    <img src="<?= $gravatar->getGravatar($mainUser->email) ?>"
                        alt="Gravatar" class="img-responsive img-rounded user-photo"
                    >
                </a>
            </div>
            <div class="media-body">
                <small class="mini"><i><?= $postDb->translateDate($mainThread->created) ?></i></small>
                <h4 class="media-heading">
                    Fråga: <?= $mainUser->username ?>
                    <small class="float-right">
                        <?= $postDb->getLikes($mainThread->id, "post", $di) ?>
                    </small>
                </h4>
                <?= $mdfilter->parse($mainThread->data) ?>
                <?php $comments = $postDb->getAllComments($mainThread->id, $di) ?>
                <?php if ($comments != null) { ?>
                    <?php foreach ($comments as $comment) : ?>
                        <?php $mainCommentUsers = $usr->getUserInfo("id", $comment->userId, $di) ?>
                        <div class="media">
                            <div class="media-left">
                                <a href="<?= url("users/user/{$mainCommentUsers->id}")?>">
                                    <img src="<?= $gravatar->getGravatar($mainCommentUsers->email) ?>"
                                        alt="Gravatar" class="img-responsive img-rounded user-photo"
                                    >
                                </a>
                            </div>
                            <div class="media-body">
                                <small class="mini"><i><?= $postDb->translateDate($comment->created) ?></i></small>
                                <h4 class="media-heading">
                                    <?= $mainCommentUsers->username ?>
                                    <small class="float-right">
                                        <?= $postDb->getLikes($comment->id, "comment", $di) ?>
                                    </small>
                                </h4>
                                <?= $mdfilter->parse($comment->data) ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php } ?>
            </div>
        </div>
        <small style="margin-bottom:50px!important">
            Kommentera <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($mainThread->id, "comment"))) ?>
        </small>
    </div>
</div>
<h2>Svar</h2>
<small>
    Lägg till ett svar <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($mainThread->id, "answer"))) ?>
</small>
<div class="container">
    <div class="row">
    <?php if ($answers != null) { ?>
        <?php foreach ($answers as $ans) : ?>
            <?php if (!in_array($ans->id, $displayedIdsT)) { ?>
                <?php $displayedIdsT[] = $ans->id ?>
                <div class="media comment-box mb-4">
                <div class="media-left">
                    <a href="<?= url("users/user/{$ans->userId}")?>">
                        <img src="<?= $gravatar->getGravatar($ans->email) ?>"
                            alt="Gravatar" class="img-responsive img-rounded user-photo"
                        >
                    </a>
                </div>
                <div class="media-body">
                    <small class="float-left"><?= $postDb->isAnswerd($ans, 1) ?></small>
                    <small class="float-left ml-3 mini"><i><?= $postDb->translateDate($ans->created) ?></i></small>
                    <small class="float-right">
                        <?= ($userIsCreator)
                            ? $postDb->getMarkAsAnswerLink(url("post/post"), $mainThread->id, $ans->id)
                            : null
                        ?></small>
                    <h4 class="media-heading">
                        Svar: <?= $ans->username ?>
                        <small class="float-right">
                            <?= $postDb->getLikes($ans->id, "post", $di) ?>
                        </small>
                    </h4>
                    <?= $mdfilter->parse($ans->data) ?>
                    <?php $subComments = $postDb->getAllComments($ans->id, $di); ?>
                    <?php foreach ($subComments as $subC) : ?>
                        <?php $commentUser = $usr->getUserInfo("id", $subC->userId, $di) ?>
                        <div class="media">
                            <div class="media-left">
                                <a href="<?= url("users/user/{$commentUser->id}")?>">
                                    <img src="<?= $gravatar->getGravatar($commentUser->email) ?>" 
                                        alt="Gravatar" class="img-responsive img-rounded user-photo"
                                    >
                                </a>
                            </div>
                            <div class="media-body">
                                <small class="mini"><i><?= $postDb->translateDate($subC->created) ?></i></small>
                                <h4 class="media-heading">
                                    <?= $commentUser->username ?>
                                <small class="float-right">
                                    <?= $postDb->getLikes($ans->id, "comment", $di) ?>
                                </small>
                                </h4>
                                <?= $mdfilter->parse($subC->data) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                </div>
                <small>
                    Kommentera <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl($ans->id, "comment"))) ?>
                </small>
                <hr class="clearfix">
            <?php } ?>
        <?php endforeach; ?>
    <?php } ?>
    </div>
</div>
