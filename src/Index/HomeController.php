<?php

namespace Anax\Index;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\UserControll\UserControll;
use Anax\Post\Post;
use Anax\Index\HTMLForm\RegisterForm;
use Anax\Index\HTMLForm\LoginForm;

/**
 * A controller that handles the index page aswell as user registration,
 *                                                     login and logout.
 */
class HomeController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handles the index page.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function indexAction(/* $args = null */) : object
    {
        $userControll = new \Anax\UserControll\UserControll;
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $viewName = "anax/v2/index/overview";

        if ($userControll->hasLoggedInUser($this->di) == null) {
            $this->di->get("response")->redirect("login");
        }

        $postDb = new Post();
        $page = $this->di->get("page");

        $page->add(
            $viewName,
            [
                "mostActiveUsers" => $user->mostActiveUsers($this->di),
                "latestQuestions" => $postDb->getLatestPosts($this->di, 2),
                "popularTags" => $postDb->findTags($this->di, 3),
                "usr" => $user,
            ]
        );

        return $page->render([
            "title" => "Översikt",
        ]);
    }

    /**
     * Renders the about page in markdown.
     */
    public function aboutAction()
    {
        $file = ANAX_INSTALL_PATH . "/content/om.md";
        $content = file_get_contents($file);
        $content = $this->di->get("textfilter")->parse(
            $content,
            ["frontmatter", "variable", "shortcode", "markdown", "titlefromheader"]
        );

        $page = $this->di->get("page");
        $page->add("anax/v2/article/default", [
            "content" => $content->text,
            "frontmatter" => $content->frontmatter,
        ]);
        return $page->render($content->frontmatter);
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
