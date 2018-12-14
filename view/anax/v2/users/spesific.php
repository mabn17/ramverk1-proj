<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;

/**
 * Displays all users in a table.
 */

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

?><h1 class="text-center">Översikt - <?= $person->username ?></h1>

<h2>Forum inlägg</h2>
<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">Inlägg</th>
            <th scope="col">Läs</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($data["posts"] as $posts) : ?>
        <tr>
            <td><?= $posts->data ?></td>
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
            <td><?= $comment->data ?></td>
            <td><a href="<?= url($usr->getCommentUrl($comment, $di)) ?>">Kolla Inlägg</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

