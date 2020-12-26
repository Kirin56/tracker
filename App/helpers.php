<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 6:38 PM
 */

/**
 * @param string $abstract
 * @return mixed
 * @throws Exception
 */
function app(string $abstract)
{
    return \App\Application::getAbstract($abstract);
}

/**
 * @param string $key
 * @return mixed
 * @throws Exception
 */
function env(string $key)
{
    $env = \App\Application::getAbstract('env');

    return $env->$key;
}

/**
 * @param string|null $path
 * @return string
 */
function config_path(?string $path = null): string
{
    if ($path === null) {
        return rtrim(CONFIG_PATH, '/');
    }

    return rtrim(CONFIG_PATH, '/') . '/' . $path;
}

/**
 * @param string|null $path
 * @return string
 */
function template_path(?string $path = null): string
{
    if ($path === null) {
        return rtrim(TEMPLATE_PATH, '/');
    }

    return rtrim(TEMPLATE_PATH, '/') . '/' . $path;
}
