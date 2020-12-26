<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 7:24 PM
 */

return [
    'database' => [
        'driver'   => \Database\Connectors\Mysql::class,
        'host'     => env('DATABASE_HOST') ?? 'localhost',
        'port'     => env('DATABASE_PORT') ?? 3306,
        'database' => env('DATABASE_NAME') ?? '',
        'user'     => env('DATABASE_USERNAME') ?? 'root',
        'password' => env('DATABASE_PASSWORD') ?? ''
    ],
    'mailer'   => [
        'driver'     => \Mailer\Google::class,
        'host'       => env('MAIL_HOST') ?? null,
        'port'       => env('MAIL_PORT') ?? null,
        'user'       => env('MAIL_USERNAME') ?? '',
        'password'   => env('MAIL_PASSWORD') ?? '',
        'encryption' => env('MAIL_ENCRYPTION') ?? 'tls'
    ]
];
