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
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                ],

                "tags" => [
                    "type" => "checkbox-multiple",
                    "class" => "",
                    "values" => $this->getMyListOfTagsForCheckBoxes(),
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
        $postClass->setDb($this->di->get("dbqb"));
        $postClass->userId = $this->form->value("userId");
        $postClass->created = $this->form->value("created");
        $postClass->title = $this->form->value("title");
        $postClass->parent = null;
        $postClass->answerd = $this->form->value("answerd");
        $postClass->data = $this->form->value("data");
        $postClass->save();
        $checkBoxes[] = "Other";

        if (!empty($this->form->value("tags"))) {
            $checkBoxes = $this->form->value("tags");
        }

        $postId = $postClass->getInformationForPost(
            $this->form->value("title"),
            $this->form->value("created"),
            $this->di
        )->id;

        foreach ($checkBoxes as $box) {
            $tag = new PostToTag();
            $tag->setDb($this->di->get("dbqb"));
            $tag->postId = $postId;
            $tag->tagId = $tag->findTagIdByName($this->di, $box);
            $tag->save();
        }
        return true;
    }

    private function getMyListOfTagsForCheckBoxes()
    {
        $postDB = new Post();
        $postDB->setDb($this->di->get("dbqb"));
        $allTags = $postDB->findTags($this->di);
        $tagList = [];
        foreach ($allTags as $tagName) {
            $tagList[] = $tagName->tag;
        }

        return $tagList;
    }


    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $title = $this->form->value("title");
        $created = $this->form->value("created");
        $db = $this->di->get('db');
        $db->connect();
        $res = $db->executeFetch(
            "SELECT id FROM Posts WHERE title = ? AND created = ?",
            [$title, $created]
        )->id;
        $this->di->get("response")->redirect("post/post/{$res}")->send();
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
        $this->form->addOutput("Något gick fel, kolla så att du har markerat minst en tag.");
        $this->di->get("response")->redirectSelf()->send();
    }
}
