<?php
/**
 * Handles kmom01 and kmom02.
 */
return [
    "routes" => [
        [
            "info" => "Flat file content controller.",
            "mount" => "validate",
            "handler" => "\Anax\Controller\IpController",
        ],
        [
            "info" => "Flat file content controller.",
            "mount" => "ip-json",
            "handler" => "\Anax\Controller\IpJsonController"
        ]
    ]
];
