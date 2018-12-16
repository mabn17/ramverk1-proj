<?php
/**
 * Handles and displays all the forum posts.
 */
return [
    "routes" => [
        [
            "info" => "Adds a post or a comment",
            "mount" => "add",
            "handler" => "\Anax\Add\AddController",
        ],
    ]
];
