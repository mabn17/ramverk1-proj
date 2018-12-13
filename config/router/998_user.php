<?php
/**
 * Handles the index, login and logout route.
 */
return [
    "routes" => [
        [
            "info" => "Användar profil.",
            "mount" => "profile",
            "handler" => "\Anax\User\ProfileController",
        ],
        [
            "info" => "Display all users",
            "mount" => "users",
            "handler" => "\Anax\User\UserController",
        ]
    ]
];
