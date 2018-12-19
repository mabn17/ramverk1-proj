<?php

namespace Anax\View;

use Anax\StyleChooser\StyleChooserController;

/**
 * A layout rendering views in defined regions.
 */
// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());
$htmlClass = $htmlClass ?? [];
$lang = $lang ?? "sv";
$charset = $charset ?? "utf-8";
$title = ($title ?? "No title") . ($baseTitle ?? " | No base title defined");
$bodyClass = $bodyClass ?? null;
// Set active stylesheet
$request = $di->get("request");
$session = $di->get("session");
if ($request->getGet("style")) {
    $session->set("redirect", currentUrl());
    redirect("style/update/" . rawurlencode($_GET["style"]));
}
// Get the active stylesheet, if any.
$activeStyle = $session->get(StyleChooserController::getSessionKey(), null);
if ($activeStyle) {
    $stylesheets = [];
    $stylesheets[] = $activeStyle;
}
// Get hgrid & vgrid
if ($request->hasGet("hgrid")) {
    $htmlClass[] = "hgrid";
}
if ($request->hasGet("vgrid")) {
    $htmlClass[] = "vgrid";
}
// Show regions
if ($request->hasGet("regions")) {
    $htmlClass[] = "regions";
}
// Get flash message if any and add to region flash-message
$flashMessage = $session->getOnce("flashmessage");
if ($flashMessage) {
    $di->get("view")->add(__DIR__ . "/../flashmessage/default", ["message" => $flashMessage], "flash-message");
}
// Get current route to make as body class
$route = "route-" . str_replace("/", "-", $di->get("request")->getRoute());
?><!doctype html>
<html <?= classList($htmlClass) ?> lang="<?= $lang ?>">
<head>
    <meta charset="<?= $charset ?>">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (isset($favicon)) : ?>
    <link rel="icon" href="<?= asset($favicon) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script defer src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
    <?php if (isset($stylesheets)) : ?>
        <?php foreach ($stylesheets as $stylesheet) : ?>
            <link rel="stylesheet" type="text/css" href="<?= asset($stylesheet) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($style)) : ?>
    <style><?= $style ?></style>
    <?php endif; ?>
</head>
<body <?= classList($bodyClass, $route) ?>>
<!-- wrapper around all items on page -->
<div class="wrap-all">
<!-- siteheader with optional columns -->
<?php if (regionHasContent("header") || regionHasContent("header-col-1")) : ?>
<div class="outer-wrap outer-wrap-header b-g-c">
    <div class="inner-wrap inner-wrap-header">
        <div class="row">
            <header class="region-header" role="banner">
                <!-- header -->
                <?php if (regionHasContent("header")) : ?>
                <div class="region-header-col-1">
                    <?php renderRegion("header") ?>
                </div>
                <?php endif; ?>
                <!-- header-col-2 -->
                <?php if (regionHasContent("header-col-2")) : ?>
                <div class="">
                    <?php renderRegion("header-col-2") ?>
                </div>
                <?php endif; ?>
                <!-- header-col-3 -->
                <?php if (regionHasContent("header-col-3")) : ?>
                <div class="region-header-col-3">
                    <?php renderRegion("header-col-3") ?>
                </div>
                <?php endif; ?>
            </header>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- navbar -->
<?php if (regionHasContent("navbar")) : ?>
<div class="outer-wrap outer-wrap-navbar">
    <div class="inner-wrap inner-wrap-navbar">
        <div class="row d-flex">
            <nav class="region-navbar justify-content-around" role="navigation">
                <?php renderRegion("navbar") ?>
            </nav>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="py-4"></div>
<!-- breadcrumb -->
<?php if (regionHasContent("breadcrumb")) : ?>
<div class="outer-wrap outer-wrap-breadcrumb py-2">
    <div class="inner-wrap inner-wrap-breadcrumb">
        <div class="row">
            <div class="region-breadcrumb">
                <?php renderRegion("breadcrumb") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- flash message -->
<?php if (regionHasContent("flash-message")) : ?>
<div class="outer-wrap outer-wrap-flash-message">
    <div class="inner-wrap inner-wrap-flash-message">
        <div class="row">
            <div class="region-flash-message">
                <?php renderRegion("flash-message") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- columns-above -->
<?php if (regionHasContent("columns-above")) : ?>
<div class="outer-wrap outer-wrap-columns-above">
    <div class="inner-wrap inner-wrap-columns-above">
        <div class="row">
            <div class="region-columns-above">
                <?php renderRegion("columns-above") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- main -->
<div class="outer-wrap outer-wrap-main py-4">
    <div class="inner-wrap inner-wrap-main">
        <div class="row">
<?php
$sidebarLeft  = regionHasContent("sidebar-left");
$sidebarRight = regionHasContent("sidebar-right");
$class = "";
$class .= $sidebarLeft  ? "has-sidebar-left "  : "";
$class .= $sidebarRight ? "has-sidebar-right " : "";
$class .= empty($class) ? "" : "has-sidebar";
?>
            <?php if ($sidebarLeft) : ?>
            <div class="wrap-sidebar region-sidebar-left <?= $class ?>" role="complementary">
                <?php renderRegion("sidebar-left") ?>
            </div>
            <?php endif; ?>
            <?php if (regionHasContent("main")) : ?>
            <main class="region-main <?= $class ?>" role="main">
                <?php renderRegion("main") ?>
            </main>
            <?php endif; ?>
            <?php if ($sidebarRight) : ?>
            <div class="wrap-sidebar region-sidebar-right <?= $class ?>" role="complementary">
                <?php renderRegion("sidebar-right") ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- after-main -->
<?php if (regionHasContent("after-main")) : ?>
<div class="outer-wrap outer-wrap-after-main">
    <div class="inner-wrap inner-wrap-after-main">
        <div class="row">
            <div class="region-after-main">
                <?php renderRegion("after-main") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- columns-below -->
<?php if (regionHasContent("columns-below")) : ?>
<div class="outer-wrap outer-wrap-columns-below">
    <div class="inner-wrap inner-wrap-columns-below">
        <div class="row">
            <div class="region-columns-below">
                <?php renderRegion("columns-below") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- sitefooter -->
<?php if (regionHasContent("footer")) : ?>
<div class="outer-wrap outer-wrap-footer" role="contentinfo">
    <div class="inner-wrap inner-wrap-footer">
        <div class="row">
            <div class="region-footer">
                <?php renderRegion("footer") ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
</div> <!-- end of wrapper -->
<!-- render javascripts -->
<?php if (isset($javascripts)) : ?>
    <?php foreach ($javascripts as $javascript) : ?>
    <script async src="<?= asset($javascript) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
<!-- useful for inline javascripts such as google analytics-->
<?php if (regionHasContent("body-end")) : ?>
    <?php renderRegion("body-end") ?>
<?php endif; ?>
</body>
</html>
