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
     * @param string $quer      The matching search param.
     * @param integer $id       The user identification.
     * @param \Psr\Container\ContainerInterface $di a service container.
     * @param boolean $multi    If you want to "login".
     *
     * @return object|null  $res The user
     */
    public function getUserInfo($quer, $id, $di, $multi = false)
    {
        $db = $this->returnDb($di);
        if (!$multi) {
            $res = $db->executeFetch("SELECT * FROM Users WHERE $quer = ?", [$id]);
            return $res;
        }

        $res = $db->executeFetch(
            "SELECT * FROM Users WHERE username = ? AND password = ?",
            [$quer, $id]
        );

        return $res;
    }

    /**
     * Returns all the comments and posts the user has made.
     *
     * @param integer $id                          The user id.
     * @param \Psr\Container\ContainerInterface $di A service container.
     *
     * @return array with all the posts and comment made by the given user id
     */
    public function getPostsAndCommentsMade($id, $di) : array
    {
        $db = $this->returnDb($di);
        $posts = $db->executeFetchAll("SELECT * FROM Posts WHERE userId = ?", [$id]);
        $comments = $db->executeFetchAll("SELECT * FROM Comments WHERE userId = ?", [$id]);

        return [
            "comments" => $comments,
            "posts" => $posts,
        ];
    }

    /**
     * Checks if its an answer or a question then response
     *                          with a url to the main thead.
     *
     * @param object $posts the current post
     *
     * @return string the url for to the main thread
     */
    public function getPostUrl($posts)
    {
        $url = ($posts->parent != null) ? $posts->parent : $posts->id;
        $start = "post/post/";
    
        return $start . $url;
    }

    /**
     * Checks the comment and returns a url to the main thread
     *                                      where it was posted.
     *
     * @param object $comment the current comment
     * @param \Psr\Container\ContainerInterface $di A service container.
     *
     * @return string the url for to the main thread
     */
    public function getCommentUrl($comment, $di)
    {
        $db = $this->returnDb($di);
        $posts = $db->executeFetch("SELECT * FROM Posts WHERE id = ?", [$comment->postId]);
        $urlP = ($posts->parent == null) ? $posts->id : $posts->parent;

        return "post/post/" . $urlP;
    }

    /**
     * Gets the most active users.
     *      Activity is ranked by nr of posts and nr of comments.
     */
    public function mostActiveUsers($di)
    {
        $db = $this->returnDb($di);
        $activeUsers = $db->executeFetchAll("CALL GetMostActiveUsers()");

        return $activeUsers;
    }

    /**
     * Calculates the users reputation, you can change how much the values -
     *           are worth in ANAX_INSTALL_PATH /config/calculate_points.php
     */
    public function calcUserPoints($id, $di)
    {
        $values = require ANAX_INSTALL_PATH . "/config/calculate_points.php";
        $db = $this->returnDb($di);

        $info = $db->executeFetch("CALL getAllUserPoints($id)");
        $comments = $db->executeFetch(
            "SELECT * FROM userLikesForComments WHERE userId = ?",
            [$id]
        )->totalPoints;
        $posts = $db->executeFetch(
            "SELECT * FROM userLikesForPosts WHERE userId = ?",
            [$id]
        )->totalPoints;

        $totalPoints =
            ($info->questionsMade * $values["question"]) +
            ($info->answersMade * $values["answer"]) +
            ($info->commentsMade * $values["comment"]) +
            (($comments + $posts) * $values["points"])
        ;

        return (string) $totalPoints;
    }

    public function calcVotesMade($id, $di)
    {
        $db = $this->returnDb($di);
        $res = $db->executeFetch(
            "SELECT COALESCE(COUNT(*), 0) AS 's' FROM Likes WHERE userId = ?",
            [$id]
        )->s;
        return (string) $res;
    }

    /**
     * Returns a connected database.
     *
     * @param \Psr\Container\ContainerInterface $di A service container.
     */
    private function returnDb($di)
    {
        $db = $di->get("db");
        $db->connect();

        return $db;
    }
}
