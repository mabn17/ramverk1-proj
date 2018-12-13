<?php

namespace Anax\View;

use Anax\StyleChooser\StyleChooserController;

/**
 * A layout rendering views in defined regions.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

$title = ($title ?? "No title") . ($baseTitle ?? " | No base title defined");

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

<?php if (isset($favicon)) : ?>
    <link rel="icon" href="<?= $favicon ?>">
<?php endif; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i" rel="stylesheet"> 
<?php if (isset($stylesheets)) : ?>
    <?php foreach ($stylesheets as $stylesheet) : ?>
        <link rel="stylesheet" type="text/css" href="<?= asset($stylesheet) ?>">
    <?php endforeach; ?>
<?php endif; ?>

</head>
<body>

<!-- header -->
<?php if (regionHasContent("header")) : ?>
    <?php renderRegion("header") ?>
<?php endif; ?>

<!-- navbar -->
<?php if (regionHasContent("navbar")) : ?>
    <?php renderRegion("navbar") ?>
<?php endif; ?>

<!-- main -->
<?php if (regionHasContent("main")) : ?>
<div class="col-md-10">
    <main class="container md-w-75 p-3">
        <?php renderRegion("main") ?>
    </main>
</div>
<?php endif; ?>

<!-- footer -->
<?php if (regionHasContent("footer")) : ?>
<div class="footer">
    <div class="bg-light border border-left-0 border-right-0 py-3 fixed-bottom d-none d-sm-block">
        <?php renderRegion("footer") ?>
    </div>
</div>
<?php endif; ?>

<?php if (isset($stylesheets)) : ?>
    <?php foreach ($javascripts as $javascript) : ?>
    <script async src="<?= asset($javascript) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
