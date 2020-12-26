<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 7:16 PM
 */

namespace Database\Connectors;


use Database\Connectors\Interfaces\Connector;
use PDO;

/**
 * Class Mysql
 * @package Database\Connectors
 */
class Mysql implements Connector
{
    /**
     * @param string $host
     * @param int $port
     * @param string $database
     * @param string $user
     * @param string|null $password
     * @return PDO
     */
    static public function connect(string $host, int $port, string $database, string $user, ?string $password = null): PDO
    {
        return new PDO("mysql:host=$host;port=$port;dbname=$database", $user, $password);
    }
}