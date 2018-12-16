<?php

namespace Anax\Add;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Index\User;
use Anax\UserControll\UserControll;
use Anax\Add\HTMLForm\CommentForm;
use Anax\Add\HTMLForm\AnswerForm;
use Anax\Add\HTMLForm\PostForm;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class AddController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Show all items.
     */
    public function indexAction() : object
    {
        $userControll = new UserControll;
        $currUser = $userControll->hasLoggedInUser($this->di);
        $request = $this->di->get("request");

        $parentId = $request->getGet('parentId') ?? null;
        $type = $request->getGet('type') ?? null;

        if ($currUser == null || $parentId == null || $type == null) {
            return $this->di->get("response")->redirect("");
        }
        if ($parentId <= 0 || !is_numeric($parentId)) {
            return $this->di->get("response")->redirect("");
        }

        $viewName = "anax/v2/add/{$type}";
        $parentId = (int) $parentId;

        $form = new CommentForm($this->di);

        if ($type == "answer") {
            $form = new AnswerForm($this->di);
        } else if ($type == "post") {
            $form = new PostForm($this->di);
        }

        $form->check();

        $page = $this->di->get("page");
        $page->add(
            $viewName,
            [
                "form" => $form->getHTML(),
            ]
        );

        return $page->render([
            "title" => "$type $parentId",
        ]);
    }
}
