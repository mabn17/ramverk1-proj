<?php

namespace Anax\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\User\HTMLForm\EdditForm;

/**
 * Displays all the users and display their posts and comments.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handles the index page.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexAction(/* $args = null */) : object
    {
        $userControll = new UserControll;
        $user = new \Anax\Index\User();
        $user->setDb($this->di->get("dbqb"));

        $viewName = "anax/v2/users/all";

        $currUser = $userControll->hasLoggedInUser($this->di);
        if ($currUser == null) {
            return $this->di->get("response")->redirect("login");
        }

        $page = $this->di->get("page");

        $page->add(
            $viewName,
            [
                "users" => $user->findAll(),
            ]
        );

        return $page->render([
            "title" => "Användare",
        ]);
    }

    /**
     * Looks up a spesific user
     *
     * @param string|int $id    the user id
     */
    public function userActionGet($id = 0)
    {
        // Checks for a valid user id
        if ($id <= 0 || !is_numeric($id)) {
            return $this->di->get("response")->redirect("users");
        }

        $userControll = new UserControll;
        $currUser = $userControll->hasLoggedInUser($this->di);
        $user = new \Anax\Index\User();
        $user->setDb($this->di->get("dbqb"));
        $id = (int) $id;
        $person = $user->getUserInfo("id", $id, $this->di);

        if ($currUser == null || $person == null) {
            return $this->di->get("response")->redirect("users");
        }

        $page = $this->di->get("page");
        $viewName = "anax/v2/users/spesific";

        $page->add(
            $viewName,
            [
                "person" => $person,
            ]
        );

        return $page->render([
            "title" => "Användare",
        ]);
    }
}
