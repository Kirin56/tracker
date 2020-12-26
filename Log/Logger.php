<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 9:23 PM
 */

namespace Log;


use Database\Builder;

/**
 * Class Logger
 * @package Log
 */
class Logger
{
    /**
     * Default log table
     */
    const TABLE = 'log';

    /**
     * @param string $message
     * @param string $status
     * @param string|null $uri
     * @throws \Exception
     */
    public static function log(string $message, string $status, ?string $uri = null)
    {
        Builder::table(self::TABLE)->insert([
            'info'   => $message,
            'uri'    => $uri,
            'status' => $status
        ]);
    }
}