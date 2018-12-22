<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());
$gravatar = new Gravatar;

?><div class="d-flex">
    <div class="col">
        <h4 class=""><i class="fa fa-user mx-3"></i> Aktiva Användare</h4>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Bild</th>
                    <th scope="col">Användarnamn</th>
                    <th scope="col">email</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($mostActiveUsers as $activePerson) : ?>
                <tr>
                    <td><img src="<?= $gravatar->getGravatar($activePerson->email) ?>" alt="Gravatar"></td>
                    <td>
                        <a href="<?= url("users/user/" . $activePerson->id) ?>"><?= $activePerson->username ?></a>
                    </td>
                    <td><?= $activePerson->email ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col mt-5">
        <h4 class=""><i class="fa fa-tags ml-5"></i> Populäraste taggar</h4>
        <div class="col ml-5">
        <?php foreach ($popularTags as $tagg) : ?>
            <a href="<?= url($postDb->getTagUrl($tagg->id)) ?>" class="no-dec">
                <span class="badge badge-info"><?= $tagg->tag ?></span>
            </a>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php foreach ($latestQuestions as $currQuest) : ?>
    <div class="col">
        <h2><a href="<?= url($usr->getPostUrl($currQuest)) ?>" class="h-link"> <?= $currQuest->title ?></a>
            <?= $postDb->isAnswerd($currQuest) ?>
        </h2>
        <?php $questUsr = $usr->getUserInfo("id", $currQuest->userId, $di) ?>
        <small><p class="lead"><i class="fa fa-user"></i> by
            <a href="<?= url("users/user/$questUsr->id") ?>"><?= $questUsr->username ?></a>
        </small>
        <span class="float-right"><small><i><?= count($postDb->getPostInfo("id", $currQuest->id, $di)["sub"]) ?> Svar |
            <?= $postDb->getLikes($currQuest->id, "post", $di, true)?>
        </i></small></span></p>
        <p><i class="fa fa-calendar"></i><span class="mx-3"><?= $postDb->translateDate($currQuest->created) ?></span>
        <i class="fa fa-tags ml-5"></i> Taggar:
        <?php $quest = $postDb->getTagsForPost($currQuest->id, $di) ?>
        <?php  foreach (explode(',', $quest) as $tname) :?>
            <a href="<?= url($postDb->getTagUrl($tname, 1, $di)) ?>" class="no-dec">
                <span class="badge badge-info"><?= $tname ?> </span>
            </a>
        <?php endforeach ?>
        </p>
    </div>
<?php endforeach; ?>
