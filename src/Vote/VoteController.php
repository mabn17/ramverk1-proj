<?php

namespace Anax\Vote;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\Vote\Like;

/**
 * Displays all the users and display their posts and comments.
 */
class VoteController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handles all the likes and dislikes.
     *
     * @param string $action Sets if its a dislike or a like.
     * @param string $type  Desides if it handles a post or a comment.
     * @param string|integer $id  The comment/post id.
     */
    private function doesThisWork($action, $type, $id)
    {
        $userControll = new UserControll();
        $cUser = $userControll->hasLoggedInUser($this->di);

        if ($cUser->id == null) {
            return $this->di->get("response")->redirect("");
        }

        $like = new Like();
        $like->setDb($this->di->get("dbqb"));

        $like->type = $type;
        $like->userId = $cUser->id;
        $like->destinationId = $id;
        $like->points = ($action == "like") ? 1 : -1;

        $like->save();
        return $this->di->get("response")->redirect("post");
    }

    /**
     * Catches the like action becuace indexAction wont snap up 3 arguments?
     *
     * @param string $type  Desides if it handles a post or a comment.
     * @param string|integer $id  The comment/post id.
     */
    public function likeAction($type, $id)
    {
        return $this->doesThisWork("like", $type, $id);
    }

    /**
     * Catches the dislike action becuace indexAction wont snap up 3 arguments?
     *
     * @param string $type  Desides if it handles a post or a comment.
     * @param string|integer $id  The comment/post id.
     */
    public function dislikeAction($type, $id)
    {
        return $this->doesThisWork("dislike", $type, $id);
    }
}
