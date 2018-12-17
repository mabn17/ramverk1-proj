<?php

namespace Anax\Add;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class PostToTag extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Post2Tags";


    /**
     * Columns in the table.
     *
     * @var integer $id      PRIMARY KEY INT AUTO_INCREMENT NOT NULL.
     * @var integer $postId      INT (FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`))
     * @var integer $tagId   INT DEFAULT 1 (FOREIGN KEY (`tagId`) REFERENCES `Tags` (`id`))
     */
    public $id;
    public $postId;
    public $tagId;
}
