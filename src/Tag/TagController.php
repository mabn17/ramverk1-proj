<?php

namespace Anax\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Displays all the users and display their posts and comments.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handles all tags.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexAction(/* $args = null */) : object
    {
        $userControll = new UserControll;
        $user = new \Anax\Index\User();
        $user->setDb($this->di->get("dbqb"));

        $viewName = "anax/v2/tags/all";

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
}
