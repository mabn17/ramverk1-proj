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
            "text" => "Hem",
            "url" => "",
            "title" => "Första sidan, börja här.",
        ],
        [
            "text" => "Redovisning",
            "url" => "redovisning",
            "title" => "Redovisningstexter från kursmomenten.",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Kmom01",
                        "url" => "redovisning/kmom01",
                        "title" => "Redovisning för kmom01.",
                    ],
                    [
                        "text" => "Kmom02",
                        "url" => "redovisning/kmom02",
                        "title" => "Redovisning för kmom02.",
                    ],
                    [
                        "text" => "Kmom03",
                        "url" => "redovisning/kmom03",
                        "title" => "Redovisning för kmom03.",
                    ],
                    [
                        "text" => "Kmom04",
                        "url" => "redovisning/kmom04",
                        "title" => "Redovisning för kmom04.",
                    ],
                    [
                        "text" => "Kmom05",
                        "url" => "redovisning/kmom05",
                        "title" => "Redovisning för kmom05.",
                    ],
                    [
                        "text" => "Kmom06",
                        "url" => "redovisning/kmom06",
                        "title" => "Redovisning för kmom06.",
                    ],
                ],
            ],
        ],
        [
            "text" => "Om",
            "url" => "om",
            "title" => "Om denna webbplats.",
        ],
        [
            "text" => "Styleväljare",
            "url" => "style",
            "title" => "Välj stylesheet.",
        ],
        [
            "text" => "Verktyg",
            "url" => "verktyg",
            "title" => "Verktyg och möjligheter för utveckling.",
        ],
        [
            "text" => "Validera IP",
            "url" => "validate",
            "title" => "En kontroller för att validera ip-adresser",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Form Valedering",
                        "url" => "validate",
                        "title" => "En kontroller för att validera ip-adresser",
                    ],
                    [
                        "text" => "Json Valedering",
                        "url" => "ip-json",
                        "title" => "En kontroller i JSON för att validera ip-adresser",
                    ],
                ],
            ],
        ],
        [
            "text" => "Väder Koll",
            "url" => "vader",
            "title" => "Väder applikation",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Väder med Form",
                        "url" => "vader",
                        "title" => "Väder applikation",
                    ],
                    [
                        "text" => "Väder med Json",
                        "url" => "v-json",
                        "title" => "Väder applikation",
                    ],
                ],
            ],
        ],
        [
            "text" => "Bok exmpl",
            "url" => "book",
            "title" => "Book Example",
        ],
    ],
];
