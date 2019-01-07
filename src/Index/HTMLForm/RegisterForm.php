<?php

namespace Anax\Index\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Index\User;

/**
 * Form to create an item.
 */
class RegisterForm extends FormModel
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

                "email" => [
                    "type" => "email",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                ],

                "password" => [
                    "type" => "password",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                ],

                "passwordTwo" => [
                    "type" => "password",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                ],

                "active" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => date("Y-m-d H:i:s"),
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skapa Konto",
                    "class"     => "btn btn-info",
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
        $user->setDb($this->di->get("dbqb"));
        $user->username = $this->form->value("username");
        $user->email = $this->form->value("email");
        $user->active = $this->form->value("active");
        $user->password = $this->form->value("password");
        $passwordTwo = $this->form->value("passwordTwo");

        if ($user->password == $passwordTwo) {
            $user->password = md5($user->password);
            $user->save();

            $dbUser = $user->getUserInfo("username", $user->username, $this->di);

            $this->di->get("session")->set("user", $dbUser);
            return true;
        }

        $this->form->rememberValues();
        $this->form->addOutput("Passwords did not match");
        return false;
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
