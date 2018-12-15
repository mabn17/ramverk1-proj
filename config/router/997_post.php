<?php
/**
 * Handles and displays all the forum posts.
 */
return [
    "routes" => [
        [
            "info" => "Displays a spesific post",
            "mount" => "post",
            "handler" => "\Anax\Post\PostController",
        ],
    ]
];
