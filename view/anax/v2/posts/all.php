<?php

namespace Anax\View;

/**
 * Displays all posts.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());



?><h1>Frågor</h1>

<?php foreach ($questions as $question) : ?>
    <?php $questUsr = $usr->getUserInfo("id", $question->userId, $di) ?>

    <h2><a href="<?= url($usr->getPostUrl($question)) ?>"> <?= $question->title ?></a></h2>
    <small>Av: <a href="<?= url("users/user/$questUsr->id") ?>"><?= $questUsr->username ?></a> - <?= $question->created ?></small>
<?php endforeach; ?>
