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
     * @var string $title       VARCHAR(200) NOT NULL.
     * @var string $data        TEXT.
     * @var integer $parent     (!FOREGEN SELF) INT DEFAULT NULL.
     * @var integer $answerd    INT DEFAULT 0 (USED AS BOOL).
     */
    public $id;
    public $userId;
    public $created;
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

    public function findPostsForMyTag($id, $di)
    {
        $db = $this->returnDb($di);
        $res = $db->executeFetchAll("SELECT * FROM HeadCommentAndTags");
        $tagId = $db->executeFetch("SELECT * FROM Tags WHERE id = $id")->tag;
        $returningArray = [];

        foreach ($res as $value) {
            $tagIds = explode(',', $value->tagss);

            if (in_array($tagId, $tagIds)) {
                $returningArray[] = $value;
            }
        }

        return [
            "data" => $returningArray,
            "tagName" => $tagId,
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
    public function getTagUrl($id, $isName = 0, $di = null) : string
    {
        $start = "tags/tag/";
        $end = $id;

        if ($isName) {
            $db = $this->returnDb($di);
            $tagId = $db->executeFetch("SELECT * FROM Tags WHERE tag = ?", [$id])->id;
            $end = $tagId;
        }

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

    /**
     * For the forum threads, takes in the information needed and returns
     *      a url to add an answer or comment.
     *
     * @param integer|string $parent    The parent id.
     * @param string $type          Checks if its a comment or answer.
     */
    public function addAnswerOrCommentUrl($parent, string $type = "answer") : string
    {
        $start = "add";
        $end = "?type=$type&parentId=$parent";

        return $start . $end;
    }

    /**
     * Returns font awsome plus sign
     *
     * @param string $url   The url
     */
    public function getPlusSign($url) : string
    {
        return "<a class='black' href='" . $url . "'><i class='fas fa-plus'></i></a>";
    }

    public function getInformationForPost($title, $created, $di)
    {
        $db = $this->returnDb($di);
        $res = $db->executeFetch("SELECT * FROM Posts WHERE title = ? AND created = ?", [$title, $created]);

        return $res;
    }
}
