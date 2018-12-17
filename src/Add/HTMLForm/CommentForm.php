<?php

namespace Anax\Add\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Add\Comment;

/**
 * Form to create an item.
 */
class CommentForm extends FormModel
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
                "userId" => [
                    "type" => "hidden",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                    "value" => $this->di->get("session")->get("user")->id,
                ],

                "postId" => [
                    "type" => "hidden",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                    "value" => $this->di->get("request")->getGet('parentId'),
                ],

                "created" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => date("Y-m-d H:i:s"),
                ],

                "data" => [
                    "type" => "textarea",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
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
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->userId = $this->form->value("userId");
        $comment->postId = $this->form->value("postId");
        $comment->created = $this->form->value("created");
        $comment->data = $this->form->value("data");
        $comment->save();

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
        $this->form->rememberValues();
        $this->form->addOutput("Något gick fel.");
        $this->di->get("response")->redirectSelf()->send();
    }
}
