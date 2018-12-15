<?php

namespace Anax\View;



/**
 * Displays all users in a table.
 */

// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

?><h1 class="text-center">Taggar</h1>

<?php foreach ($tags as $tag) : ?>
    <p><a href="<?= url($postDb->getTagUrl($tag->id) ) ?>"><?= $tag->tag ?></a></p>
<?php endforeach;

