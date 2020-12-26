<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 6:32 PM
 */

define('ENVIRONMENT_FILE', __DIR__ . '/.env');

define('CONFIG_PATH', __DIR__ . '/App/config');
define('TEMPLATE_PATH', __DIR__ . '/template');

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Europe/Moscow');

$app = new \App\Application;

$app->exec();
