<?php

namespace Anax\View;

/**
 * View to register a new user.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());



?><h1>Registrera ett nytt konto</h1>

<?= $form ?>

Redan medlem? <a href="<?= url("login") ?>">Logga in</a>