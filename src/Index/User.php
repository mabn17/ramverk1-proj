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
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $username;
    public $password;
    public $email;
    public $points;

    public function getUserInfo($quer, $id, $di)
    {
        $db = $di->get("db");
        $db->connect();
        $res = $db->executeFetch("SELECT * FROM Users WHERE $quer = ?", [$id]);

        return $res;
    }
}
