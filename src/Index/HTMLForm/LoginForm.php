<?php

namespace Anax\Index\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Index\User;

/**
 * Form to create an item.
 */
class LoginForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param \Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "username" => [
                    "type" => "text",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                ],

                "password" => [
                    "type" => "password",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Logga in",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $user = new User();
        $otherUser = new User();
        $user->setDb($this->di->get("dbqb"));
        $otherUser->setDb($this->di->get("dbqb"));

        $username = $this->form->value("username");
        $password = $this->form->value("password");

        $dbUser = $user->getUserInfo("username", $username, $this->di);

        if (!$dbUser || md5($password) != $dbUser->password) {
            $this->form->rememberValues();
            $this->form->addOutput("Username or Password did not match!");
            return false;
        }

        $otherUser->find("id", $dbUser->id);
        $otherUser->active = date("Y-m-d H:i:s");
        $otherUser->save();

        $this->di->get("session")->set("user", $dbUser);
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("")->send();
    }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    public function callbackFail()
    {
        $this->di->get("response")->redirectSelf()->send();
    }
}
