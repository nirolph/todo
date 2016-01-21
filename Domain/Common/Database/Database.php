<?php
namespace Domain\Common\Database;

/**
 * Description of Database
 *
 * @author florin
 */
class Database
{
    public static function connect()
    {
        return new \PDO("mysql:host=localhost;dbname=task;charset=utf8", 'root', 'vagrant');
    }
}
