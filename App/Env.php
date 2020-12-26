<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 6:58 PM
 */

namespace App;


use Exception;

/**
 * Class Env
 * @package App
 */
class Env
{
    /**
     * @var string
     */
    private $origin;

    /**
     * @var array
     */
    private $settings;

    /**
     * Env constructor.
     * @throws Exception
     */
    public function __construct()
    {
        if (!file_exists(ENVIRONMENT_FILE)) {
            throw new Exception('Environment file not found');
        }

        $this->origin = file_get_contents(ENVIRONMENT_FILE);
        $this->settings = $this->map($this->origin ?? '');
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        if (!array_key_exists($key, $this->settings)) {
            return null;
        }

        return $this->settings[$key];
    }

    /**
     * @param string $data
     * @return array
     */
    protected function map(string $data): array
    {
        $array = [];

        foreach (explode("\n", $data) as $line) {
            $setting = explode('=', $line);

            if (count($setting) > 1) {
                $array[$setting[0]] = $setting[1];
            }
        }

        return $array;
    }
}