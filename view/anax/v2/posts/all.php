<?php

namespace Anax\View;

/**
 * Displays all posts.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?><h1>Alla Frågor</h1>
Lägg till en ny fråga <?= $postDb->getPlusSign(url($postDb->addAnswerOrCommentUrl(1, "post"))) ?>

<?php foreach ($questions as $question) : ?>
    <div class="py-3">
        <h1><a href="<?= url($usr->getPostUrl($question)) ?>" class="h-link"> <?= $question->title ?></a> <?= $postDb->isAnswerd($question) ?></h1>
        <?php $questUsr = $usr->getUserInfo("id", $question->userId, $di) ?>
        <small><p class="lead"><i class="fa fa-user"></i> by <a href="<?= url("users/user/$questUsr->id") ?>"><?= $questUsr->username ?></a></small>
        <span class="float-right"><small><i><?= count($postDb->getPostInfo("id", $question->id, $di)["sub"]) ?> Svar | <?= $postDb->getLikes($question->id, "post", $di, true)?></i></small></span></p>
        <p><i class="fa fa-calendar"></i><span class="mx-3"><?= $postDb->translateDate($question->created) ?></span>
        <i class="fa fa-tags ml-5"></i> Taggar:
        <!-- ??? -->
        <?php $quest = $postDb->getTagsForPost($question->id, $di) ?>
        <?php  foreach (explode(',', $quest) as $tname) :?>
            <a href="<?= url($postDb->getTagUrl($tname, 1, $di)) ?>" class="no-dec">
                <span class="badge badge-info"><?= $tname ?> </span>
            </a>
        <?php endforeach ?>
        </p>
    </di>
<?php endforeach; ?>
