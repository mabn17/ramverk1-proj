<?php

namespace Anax\Post;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Post extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Posts";


    /**
     * Columns in the table.
     *
     * @var integer $id         PRIMARY KEY INT AUTO_INCREMENT NOT NULL.
     * @var integer $userId     (FOREGEN - Users) INT NOT NULL.
     * @var string $created     TIMESTAMP DEFAULT CURRENT_TIMESTAMP.
     * @var integer $likes      INT DEFAULT 0.
     * @var string $title       VARCHAR(200) NOT NULL.
     * @var string $data        TEXT.
     * @var integer $parent     (!FOREGEN SELF) INT DEFAULT NULL.
     * @var integer $answerd    INT DEFAULT 0 (USED AS BOOL).
     */
    public $id;
    public $userId;
    public $created;
    public $likes;
    public $title;
    public $data;
    public $parent;
    public $answerd;

    /**
     * Returns the user
     *
     * @param string $quer      The matching search param
     * @param integer $id       The post identification
     * @param Psr\Container\ContainerInterface $di a service container
     *
     * @return object|null  $res The user
     */
    public function getPostInfo($quer, $id, $di)
    {
        $db = $this->returnDb($di);

        $res = $db->executeFetch("SELECT * FROM HeadCommentAndTags WHERE $quer = ?", [$id]);
        $resTwo = $db->executeFetchAll("SELECT * FROM GetSubPosts WHERE parent = $id");

        return [
            "main" => $res,
            "sub" => $resTwo,
        ];
    }

    /**
     * Returns all the comments and answers for the thread.
     *
     * @param Psr\Container\ContainerInterface $di A service container.
     * @param integer $amount                      Nr of posts.
     *
     * @return array with all the answers and comment ind the given post thread
     */
    public function getLatestPosts($di, $amount) : array
    {
        $db = $this->returnDb($di);
        $sql = "SELECT * FROM Posts WHERE parent IS NULL ORDER BY id DESC LIMIT $amount";

        if (!$amount) {
            $sql = "SELECT * FROM Posts WHERE parent IS NULL ORDER BY id DESC";
        }
        $posts = $db->executeFetchAll($sql);

        return $posts;
    }

    /**
     * Checks the database and returns all tags.
     *
     * @param integer $limit    Default 0, The number of tags returned.
     */
    public function findTags($di, $limit = 0)
    {
        $db = $this->returnDb($di);

        if ($limit) {
            $tags = $db->executeFetchAll("CALL GetPopularTags()");
            return array_slice($tags, 0, $limit, true);
        }

        return $db->executeFetchAll("CALL GetPopularTags()");
    }

    /**
     * Returns a url so the user can look at spesific tags
     */
    public function getTagUrl($id) : string
    {
        $start = "tags/tag/";
        $end = $id;

        return $start . $end;
    }

    /**
     * Checks for all comments to the given id
     *
     * @param integer $id   the post id
     */
    public function getAllComments($id, $di)
    {
        $db = $this->returnDb($di);
        $comments = $db->executeFetchAll("SELECT * FROM Comments WHERE postId = ?", [$id]);

        return $comments;
    }

    /**
     * Returns a connected database.
     *
     * @param Psr\Container\ContainerInterface $di A service container.
     */
    private function returnDb($di)
    {
        $db = $di->get("db");
        $db->connect();

        return $db;
    }
}
