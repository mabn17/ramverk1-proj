<?php
/**
 * Handles the index, login and logout route.
 */
return [
    "routes" => [
        [
            "info" => "Index route.",
            "mount" => null,
            "handler" => "\Anax\Index\HomeController",
        ],
        [
            "info" => "Logout",
            "mount" => "logout",
            "handler" => "\Anax\Index\HomeController",
        ],
        [
            "info" => "Login",
            "mount" => "login",
            "handler" => "\Anax\Index\HomeController",
        ]
    ]
];
