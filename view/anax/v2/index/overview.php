<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

//$activeUsers =  $usr->mostActiveUsers($di);

$gravatar = new Gravatar;


/* <h1>Populäraste taggar</h1>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">Namn</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($popularTags as $tag) : ?>
        <tr>
            <td><a href="<?= url("tags/tag/$tag->id") ?>"><?= $tag->tag ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> */


/* <h1>Senaste frågor</h1>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">Titel</th>
            <th scope="col">Skapad</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($latestQuestions as $question) : ?>
        <tr>
            <td><a href="<?= url($usr->getPostUrl($question, $di)) ?>"><?= $question->title ?></a></td>
            <td><?= $question->created ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> */


/* <h1>Mest aktiva användare</h1>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">Användarnamn</th>
            <th scope="col">email</th>
            <th scope="col">Bild</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($mostActiveUsers as $person) : ?>
        <tr>
            <td><a href="<?= url("users/user/" . $person->id) ?>"><?= $person->username ?></a></td>
            <td><?= $person->email ?></td>
            <td><img src="<?= $gravatar->getGravatar($person->email) ?>" alt="Gravatar"></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> */

?>
<div class="d-flex">
    <div class="col">
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Bild</th>
                    <th scope="col">Användarnamn</th>
                    <th scope="col">email</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($mostActiveUsers as $person) : ?>
                <tr>
                    <td><img src="<?= $gravatar->getGravatar($person->email) ?>" alt="Gravatar"></td>
                    <td><a href="<?= url("users/user/" . $person->id) ?>"><?= $person->username ?></a></td>
                    <td><?= $person->email ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col">
        <h4 class=""><i class="fa fa-tags ml-5"></i> Populäraste taggar</h4>

        <div class="col ml-5">
        <?php foreach ($popularTags as $tag) : ?>
            <a href="<?= url($postDb->getTagUrl($tag->id)) ?>" class="no-dec">
                <span class="badge badge-info"><?= $tag->tag ?></span>
            </a>
        <?php endforeach; ?>
        </div>
    </div>
</div>



<?php foreach ($latestQuestions as $question) : ?>
    <div class="col">
        <h2><a href="<?= url($usr->getPostUrl($question)) ?>" class="h-link"> <?= $question->title ?></a> <?= $postDb->isAnswerd($question) ?></h2>
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
    </div>
<?php endforeach; ?>
