<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Översikt",
            "url" => "",
            "title" => "Översikt.",
        ],
        [
            "text" => "Om",
            "url" => "about",
            "title" => "Om",
        ],
        [
            "text" => "Profil",
            "url" => "profile",
            "title" => "Profil",
        ],
        [
            "text" => "Användare",
            "url" => "users",
            "title" => "Användare",
        ],
        [
            "text" => "Taggar",
            "url" => "tags",
            "title" => "taggar",
        ],
        [
            "text" => "Inlägg",
            "url" => "post",
            "title" => "inlägg",
        ]
    ],
];
