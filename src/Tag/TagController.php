<?php

namespace Anax\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\Post\Post;
use Anax\Index\User;

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

    /**
     * Displays all questions for a spesific tag.
     *
     * @param integer $id   The tag id - defaults to 0.
     */
    public function tagActionGet($id = 0)
    {
        if ($id <= 0 || !is_numeric($id)) {
            return $this->di->get("response")->redirect("tags");
        }

        $userControll = new UserControll;
        $currUser = $userControll->hasLoggedInUser($this->di);

        if ($currUser == null) {
            return $this->di->get("response")->redirect("login");
        }

        $id = (int) $id;
        $postDb = new Post();
        $user = new User();
        $postDb->setDb($this->di->get("dbqb"));
        $user->setDb($this->di->get("dbqb"));
        $postData = $postDb->findPostsForMyTag($id, $this->di);
        $postWithThisTag = $postData["data"];
        $tagName = $postData["tagName"];

        $page = $this->di->get("page");
        $viewName = "anax/v2/tags/spesific";

        $page->add(
            $viewName,
            [
                "data" => $postWithThisTag,
                "tagName" => $tagName,
                "usr" => $user,
            ]
        );

        return $page->render([
            "title" => "Tag - $id",
        ]);
    }
}
