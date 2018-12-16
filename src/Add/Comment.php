<?php

namespace Anax\Add;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Comment extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Comments";


    /**
     * Columns in the table.
     *
     * @var integer $id      PRIMARY KEY INT AUTO_INCREMENT NOT NULL.
     * @var string $created  TIMESTAMP DEFAULT CURRENT_TIMESTAMP.
     * @var integer $userId  INT NOT NULL (linked to users.id).
     * @var integer $postId  INT NOT NULL (linked to posts.id).
     * @var string $data     TEXT.
     */
    public $id;
    public $created;
    public $userId;
    public $postId;
    public $data;
}
