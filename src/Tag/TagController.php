<?php

namespace Anax\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\Post\Post;

/**
 * Displays all the users and display their posts and comments.
 */
class TagController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handles all tags.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexActionGet() : object
    {
        $userControll = new UserControll;
        $currUser = $userControll->hasLoggedInUser($this->di);

        if ($currUser == null) {
            return $this->di->get("response")->redirect("login");
        }

        $postDb = new Post();
        $postDb->setDb($this->di->get("dbqb"));

        $page = $this->di->get("page");
        $viewName = "anax/v2/tags/all";

        $page->add(
            $viewName,
            [
                "tags" => $postDb->findTags($this->di),
                "postDb" => $postDb,
            ]
        );

        return $page->render([
            "title" => "Taggar",
        ]);
    }
}
