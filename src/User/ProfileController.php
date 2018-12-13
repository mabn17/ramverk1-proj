<?php

namespace Anax\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\User\HTMLForm\EdditForm;

/**
 * A controller for flat file markdown content.
 */
class ProfileController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handles the index page.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexAction($args = null) : object
    {
        $userControll = new \Anax\UserControll\UserControll;
        $user = new \Anax\Index\User();
        $user->setDb($this->di->get("dbqb"));

        $viewName = "anax/v2/user/profile";

        $currUser = $userControll->hasLoggedInUser($this->di);
        if ($currUser == null) {
            return $this->di->get("response")->redirect("login");
        }

        $page = $this->di->get("page");
        $form = new EdditForm($this->di, $currUser);
        $form->check();

        $page->add(
            $viewName,
            [
                "form" => $form->getHTML(),
                "currUser" => $currUser,
            ]
        );

        return $page->render([
            "title" => "Profil",
        ]);
    }
}
