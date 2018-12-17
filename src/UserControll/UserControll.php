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

    /**
     * Takes a list of arguments then returns true if one is null.
     *
     * @param array $validationArray The list with arugments.
     *
     * @return bool ture|false
     */
    public function validate($validationArray)
    {
        foreach ($validationArray as $part) {
            if ($part == null) {
                return true;
            }
        }
        return false;
    }
}
