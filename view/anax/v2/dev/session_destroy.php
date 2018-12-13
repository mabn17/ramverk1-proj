<?php

namespace Anax\View;

/**
 * A layout rendering views in defined regions.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$mount = $mount ?? null; // Where are the routes mounted
$session = $di->get("session");
$session->destroy();

/* <pre><?= var_dump($session) ?></pre> */

?><h1>Session destroyed</h1>

<p>The session is now destroyed.</p>


<p>
    <a href="<?= url($mount."session") ?>">Back to session<a>
</p>
