<?php

namespace Anax\Index;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\Index\HTMLForm\RegisterForm;
use Anax\Index\HTMLForm\LoginForm;

/**
 * A controller for flat file markdown content.
 */
class HomeController implements ContainerInjectableInterface
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
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $viewName = "anax/v2/index/default";

        if ($userControll->hasLoggedInUser($this->di) == null) {
            $this->di->get("response")->redirect("login");
        }

        $page = $this->di->get("page");
        $form = new RegisterForm($this->di);
        $form->check();

        $page->add(
            $viewName,
            [
                "items" => $user->findAll(),
            ]
        );

        return $page->render([
            "title" => "Hem",
        ]);
    }

    /**
     * Lets the user Logout
     */
    public function logoutAction()
    {
        $this->di->get("session")->delete("user");
        $this->di->get("response")->redirect("");
    }

    /**
     * Lets the user login
     */
    public function loginAction()
    {
        $page = $this->di->get("page");
        $form = new LoginForm($this->di);
        $form->check();

        $viewName = "anax/v2/user/login";
        $page->add(
            $viewName,
            [
                "form" => $form->getHTML(),
            ]
        );

        return $page->render([
            "title" => "Logga in",
        ]);
    }

    /**
     * Lets the user register
     */
    public function registerAction()
    {
        $page = $this->di->get("page");
        $form = new RegisterForm($this->di);
        $form->check();

        $viewName = "anax/v2/user/register";
        $page->add(
            $viewName,
            [
                "form" => $form->getHTML(),
            ]
        );

        return $page->render([
            "title" => "Registrera",
        ]);
    }
}
