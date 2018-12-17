<?php

namespace Anax\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\Index\User;

/**
 * A controller for flat file markdown content.
 */
class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Displays all posts and links to their threads.
     */
    public function indexActionGet()
    {
        $userControll = new UserControll;
        $currUser = $userControll->hasLoggedInUser($this->di);

        if ($currUser == null) {
            return $this->di->get("response")->redirect("");
        }

        $postDb = new Post();
        $user = new User();

        $postDb->setDb($this->di->get("dbqb"));

        $page = $this->di->get("page");
        $viewName = "anax/v2/posts/all";

        $questions = $postDb->getLatestPosts($this->di, 0);

        $page->add(
            $viewName,
            [
                "questions" => $questions,
                "usr" => $user,
                "postDb" => $postDb,
            ]
        );

        return $page->render([
            "title" => "Alla inlägg",
        ]);
    }

    /**
     * Displays a spesifc thread by the mail post id.
     *
     * @param string|int $id    the user id
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function postActionGet($id = 0, $isAnswerd = 0) : object
    {
        // Checks for a valid post id
        if ($id <= 0 || !is_numeric($id)) {
            return $this->di->get("response")->redirect("");
        }

        $userControll = new UserControll;
        $currUser = $userControll->hasLoggedInUser($this->di);
        $id = (int) $id;
        $postDb = new Post();
        $usr = new User();

        $postDb->setDb($this->di->get("dbqb"));

        // !UPDTADE SO IT ALSO ONLT GETS WHERE PARENT == NULL!
        $info = $postDb->getPostInfo("id", $id, $this->di);
        $mainThread = $info["main"];
        $ans = $info["sub"];

        if ($userControll->validate([$currUser, $mainThread])) {
            return $this->di->get("response")->redirect("");
        }

        if ($isAnswerd != 0) {
            $postDb->markPostAsAnswerd($isAnswerd, $id, $this->di);
            return $this->di->get("response")->redirect("post/post/$id");
        }

        $mainUser = $usr->getUserInfo("id", $mainThread->userId, $this->di);
        $page = $this->di->get("page");
        $viewName = "anax/v2/posts/thread";

        $page->add(
            $viewName,
            [
                "mainThread" => $mainThread,
                "mainUser" => $mainUser,
                "admin" => $currUser,
                "usr" => $usr,
                "answers" => $ans,
                "postDb" => $postDb,
            ]
        );

        return $page->render([
            "title" => "Post $id",
        ]);
    }
}
