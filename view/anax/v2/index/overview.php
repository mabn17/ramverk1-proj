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

?><h1>Populäraste taggar</h1>
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
</table>

<h1>Senaste frågor</h1>
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
</table>

<h1>Mest aktiva användare</h1>
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
</table>