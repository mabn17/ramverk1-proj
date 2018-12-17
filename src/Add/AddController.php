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

        $parentId = $request->getGet('parentId');
        $type = $request->getGet('type');

        if ($currUser == null || !is_numeric($parentId)) {
            return $this->di->get("response")->redirect("post");
        }

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
            "anax/v2/add/{$type}",
            [
                "form" => $form->getHTML(),
            ]
        );

        return $page->render([
            "title" => "$type $parentId",
        ]);
    }
}
