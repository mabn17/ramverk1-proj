<?php

namespace Anax\Vote;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Like extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Likes";



    /**
     * Columns in the table.
     *
     * @var integer $id              PRIMARY KEY INT AUTO_INCREMENT NOT NULL.
     * @var string $type                    VARCHAR(20) ("post" or "comment")
     * @var integer $userId  FOREIGN KEY (`userId`) REFERENCES `Users` (`id`)
     * @var integer $destinationId  INT (points to posts(id) or comments(id))
     * @var integer $points                           INT DEFAULT 0 NOT NULL.
     */
    public $id;
    public $type;
    public $userId;
    public $destinationId;
    public $points;

    public function findNrOfLikes($type, $id, $di)
    {
        $db = $di->get("db");
        $db->connect();

        $res = $db->executeFetch(
            "SELECT SUM(points) AS 'total' FROM Likes WHERE type = ? AND destinationId = ?",
            [$type, $id]
        );

        $points = ($res->total != null) ? $res->total : 0;
        return $points;
    }
}
