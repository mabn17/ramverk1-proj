<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("book/create");
$urlToDelete = url("book/delete");



?><h1>View all items</h1>

<p>
    <a href="<?= $urlToCreate ?>">Create</a> | 
    <a href="<?= $urlToDelete ?>">Delete</a>
</p>

<?php if (!$items) : ?>
        <p>There are no items to show.</p>
    <?php
    return;
endif;
?>

<table class="table">
<thead class="thead-light">
    <tr>
        <th scope="col">Id</th>
        <th scope="col">Title</th>
        <th scope="col">Author</th>
        <th scope="col">Picture</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td>
            <a href="<?= url("book/update/{$item->id}"); ?>"><?= $item->id ?></a>
        </td>
        <td><?= $item->title ?></td>
        <td><?= $item->author ?></td>
        <td><img class="img-thumbnail" style="max-height: 115px;max-width: 115px;" src="<?= $item->picture ?>" alt="Img Not Found"></td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
