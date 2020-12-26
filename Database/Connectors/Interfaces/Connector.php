<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 7:18 PM
 */

namespace Database\Connectors\Interfaces;


use PDO;

/**
 * Interface Connector
 * @package Database\Connectors\Interfaces
 */
interface Connector
{
    /**
     * @param string $host
     * @param int $port
     * @param string $database
     * @param string $user
     * @param string|null $password
     * @return PDO
     */
    static public function connect(string $host, int $port, string $database, string $user, ?string $password = null): PDO;
}
