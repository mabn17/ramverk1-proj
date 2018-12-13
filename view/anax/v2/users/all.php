<?php

namespace Anax\View;

use Anax\Gravatar\Gravatar;

/**
 * Displays all users in a table.
 */

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());
$users = isset($users) ? $users : null;
$gravatar = new Gravatar;

?><h1 class="text-center">Användare</h1>
<?php if ($users) { ?>
    <table class="table">
        <thead class="thead-light">
            <tr>
                <th scope="col">Användarnamn</th>
                <th scope="col">email</th>
                <th scope="col">Bild</th>
                <th scope="col">Poäng</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $person) : ?>
            <tr>
                <td><a href="<?= url("users/user/" . $person->id) ?>"><?= $person->username ?></a></td>
                <td><?= $person->email ?></td>
                <td><img src="<?= $gravatar->getGravatar($person->email) ?>" alt="Gravatar"></td>
                <td><?= $person->points ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php }
