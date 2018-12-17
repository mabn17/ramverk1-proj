<?php

namespace Anax\Add\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Post\Post;
use Anax\Add\PostToTag;

/**
 * Form to create an item.
 */
class PostForm extends FormModel
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

                "created" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "value" => date("Y-m-d H:i:s"),
                ],

                "title" => [
                    "type" => "text",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                    "value" => "",
                ],

                "answerd" => [
                    "type" => "hidden",
                    "class" => "form-control w-50",
                    "validation" => ["not_empty"],
                    "value" => "0",
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
        $postClass = new Post();
        $tag = new PostToTag();
        $postClass->setDb($this->di->get("dbqb"));
        $tag->setDb($this->di->get("dbqb"));
        $postClass->userId = $this->form->value("userId");
        $postClass->created = $this->form->value("created");
        $postClass->title = $this->form->value("title");
        $postClass->parent = null;
        $postClass->answerd = $this->form->value("answerd");
        $postClass->data = $this->form->value("data");
        $postClass->save();

        $tag->postId = $postClass->getInformationForPost(
            $this->form->value("title"),
            $this->form->value("created"),
            $this->di
        )->id;
        $tag->tagId = 1;
        $tag->save();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("post")->send();
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
