<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "id" => "rm-menu",
    "wrapper" => null,
    "class" => "rm-default rm-mobile",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Översikt",
            "url" => "",
            "title" => "Översikt.",
        ],
        [
            "text" => "Om",
            "url" => "om",
            "title" => "Om denna webbplats.",
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
        ],
        [
            "text" => "API",
            "url" => "api",
            "title" => "Api"
        ]
    ],
];
