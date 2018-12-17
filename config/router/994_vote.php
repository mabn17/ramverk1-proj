<?php
/**
 * Handles the voting.
 */
return [
    "routes" => [
        [
            "info" => "Votes for a post or a comment",
            "mount" => "vote",
            "handler" => "\Anax\Vote\VoteController",
        ],
    ]
];
