<?php
/**
 * Handles kmom01 and kmom02.
 */
return [
    "routes" => [
        [
            "info" => "Väder applikation",
            "mount" => "vader",
            "handler" => "\Anax\Controller\WeatherController",
        ],
        [
            "info" => "Väder applikation",
            "mount" => "v-json",
            "handler" => "\Anax\Controller\WeatherJsonController",
        ],
    ]
];
