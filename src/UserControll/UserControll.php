<?php

namespace Anax\UserControll;

/**
 * Handles user acitivity
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class UserControll
{
    /**
     * Checks if a user is logged in.
     *
     * @return bool ture|false
     */
    public function hasLoggedInUser($di)
    {
        $session = $di->get("session");
        $user = ($session->has("user")) ? $session->get("user") : null;
        return $user;
    }
}
