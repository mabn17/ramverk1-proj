<?php

namespace Anax\View;

/**
 * Displays all users in a table.
 */

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());
$inde = 0;
$colorList = [
    "badge badge-primary",
    "badge badge-secondary",
    "badge badge-success",
    "badge badge-info",
    "badge badge-light",
];

?><h2 class=""><i class="fa fa-tags ml-5"></i> Taggar:</h2>

<?php foreach ($tags as $tag) : ?>
    <a href="<?= url($postDb->getTagUrl($tag->id)) ?>" class="no-dec">
        <span class="badge badge-info"><?= $tag->tag ?></span>
    </a> 
<?php endforeach; ?>

<div class="py-4 mb-5"></div>