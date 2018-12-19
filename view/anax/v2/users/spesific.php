<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;
use Anax\MdFilter\MdFilter;

/**
 * Displays all users in a table.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());
$mdfilter = new MdFilter;
$gravatar = new Gravatar;
?><img src="<?= $gravatar->getGravatar($person->email) ?>" alt="Gravatar">
<br><small>Ryckte: <?= $usr->calcUserPoints($person->id, $di) ?></small>
<br><small>Antal Röster Lagda: <?= $usr->calcVotesMade($person->id, $di) ?> </small>
<h1 class="text-center">Översikt - <?= $person->username ?></h1>
<h2>Forum inlägg</h2>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">Typ</th>
            <th scope="col">Inlägg</th>
            <th scope="col">Läs</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data["posts"] as $posts) : ?>
        <tr>
            <td><?= ($posts->parent !== null) ? "Svar" : "Fråga" ?></td>
            <td><?= mb_substr($mdfilter->parse($posts->data), 0, 30) . " ..." ?></td>
            <td><a href="<?= url($usr->getPostUrl($posts)) ?>">Kolla Inlägg</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h2>Kommentarer</h2>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">Kommentar</th>
            <th scope="col">Läs</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data["comments"] as $comment) : ?>
        <tr>
            <td><?= mb_substr($mdfilter->parse($comment->data), 0, 30) . " ..." ?></td>
            <td><a href="<?= url($usr->getCommentUrl($comment, $di)) ?>">Kolla Inlägg</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
