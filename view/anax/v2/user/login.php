<?php

namespace Anax\View;

/**
 * View to login.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());



?><h1>Logga in</h1>

<?= $form ?>

<a href="<?= url("register") ?>">Registrera dig</a>