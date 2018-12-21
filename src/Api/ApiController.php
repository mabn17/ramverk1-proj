<?php

namespace Anax\Api;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Vote\Like;
use Anax\Index\User;
use Anax\UserControll\UserControll;

/**
 * Handles the api.
 */
class ApiController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Index route - Displays the documentation.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");
        $viewName = "anax/v2/api/documentation";

        $page->add(
            $viewName,
            []
        );

        return $page->render([
            "title" => "Documentation",
        ]);
    }

    /**
     * Returns the number of points for the given comment or post.
     *
     * @param string $type  If its a post or a comment.
     * @param integer|string $id    the id of the given post/comment.
     *
     * @return array    The number of points/error msg in a JSON format.
     */
    public function getAction(string $type = "null", $id = 0) : array
    {
        $accepteble = ["comment", "post"];
        if (!in_array($type, $accepteble) || $id == 0 || !is_numeric($id)) {
            return [
                [
                    "error" => "Invalid values 'type': {$type} 'id': {$id}"
                ]
            ];
        }

        $id = (int) $id;
        $like = new Like();
        $like->setDb($this->di->get("dbqb"));
        $points = $like->findNrOfLikes($type, $id, $this->di);

        return [
            [
                "points" => $points
            ]
        ];
    }

    /**
     * Handles voting on posts and comments thought api.
     *
     * @param string $unam The username.
     * @param string|integer $pass  The password.
     * @param string $acti Do you want to like/dislike?
     * @param string $type  If its a comment or a post.
     * @param string|integer $id The id of the post/comment.
     *
     * @return array    An array in JSON format with the requested information.
     */
    public function voteAction($unam = "", $pass = "", $acti = "", $type = "", $id = 0) : array
    {
        $user = new User;
        $userControll = new UserControll;
        $user->setDb($this->di->get("dbqb"));
        $givenUser = $user->getUserInfo($unam, MD5($pass), $this->di, true);

        if ($userControll->isValid($givenUser, $type, $acti, $id) == "unvalid") {
            return [
                [ "error" => "Invalid arguments"]
            ];
        }

        $id = (int) $id;
        $likeDb = new Like;
        $likeDb->setDb($this->di->get("dbqb"));
        $request = $this->di->get("request");
        $methods = $request->getGet('methods');
        $likeDb->type = $type;
        $likeDb->destinationId = $id;
        $likeDb->points = ($acti === 'dislike') ? -1 : 1;
        $likeDb->userId = $givenUser->id;
        $likeDb->save();

        if ($methods == 'both') {
            return $this->getAction($type, $id);
        }

        return [
            [ "status" => "ok" ]
        ];
    }
}
