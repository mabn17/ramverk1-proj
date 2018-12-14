<?php

namespace Anax\Index;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class User extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Users";



    /**
     * Columns in the table.
     *
     * @var integer $id         PRIMARY KEY INT AUTO_INCREMENT NOT NULL.
     * @var string $username    VARCHAR(23) UNIQUE NOT NULL
     * @var string $password    CHAR(200) NOT NULL
     * @var string $email       VARCHAR(60) UNIQUE NOT NULL
     * @var integer $points     INT DEFAULT 0 NOT NULL
     */
    public $id;
    public $username;
    public $password;
    public $email;
    public $points;

    /**
     * Returns the user
     *
     * @param string $quer      The matching search param
     * @param integer $id       The user id
     * @param Psr\Container\ContainerInterface $di a service container
     *
     * @return object|null  $res The user
     */
    public function getUserInfo($quer, $id, $di)
    {
        $db = $di->get("db");
        $db->connect();
        $res = $db->executeFetch("SELECT * FROM Users WHERE $quer = ?", [$id]);

        return $res;
    }

    /**
     * Returns all the comments and posts the user has made.
     *
     * @param integer $id                          The user id.
     * @param Psr\Container\ContainerInterface $di A service container.
     *
     * @return array with all the posts and comment made by the given user id
     */
    public function getPostsAndCommentsMade($id, $di) : array
    {
        $db = $di->get("db");
        $db->connect();
        $posts = $db->executeFetchAll("SELECT * FROM Posts WHERE userId = ?", [$id]);
        $comments = $db->executeFetchAll("SELECT * FROM Comments WHERE userId = ?", [$id]);

        return [
            "comments" => $comments,
            "posts" => $posts,
        ];
    }

    public function getPostUrl($posts)
    {
        $url = ($posts->parent != null) ? $posts->parent : $posts->id;
        $start = "post/";
    
        return $start . $url;
    }

    public function getCommentUrl($comment, $di)
    {
        $db = $di->get("db");
        $db->connect();

        $posts = $db->executeFetch("SELECT * FROM Posts WHERE id = ?", [$comment->postId]);
        
        $urlP = ($posts->parent == null) ? $posts->id : $posts->parent;

        return "post/" . $urlP;
    }
}
