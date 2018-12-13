<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;
/**
 * View to register a new user.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

$currUser = isset($currUser) ? $currUser : null;
$gravatar = new Gravatar;


// For debugging for dubblechecking on failed uppdate on email or username
// (Forms not reverting back to main value on failed result)
// echo $currUser->email


if (!$currUser) {
    echo "<p>You are not logged in</p>";
    return;
}

?><h1>Hej <?= $currUser->username ?></h1>
<div class="float-right d-flex"><img src="<?= $gravatar->getGravatar($currUser->email) ?>" alt="Gravatar"></div>

<?= $form ?>

<a href="<?= url("logout") ?>">Logga ut</a>