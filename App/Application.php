<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 6:33 PM
 */

namespace App;


use Exception;

/**
 * Class Application
 * @package App
 */
class Application
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var array
     */
    protected static $container = [];

    /**
     * Application constructor.
     * @throws Exception
     */
    public function __construct()
    {
        self::$container['env'] = new Env;

        $this->configuration = $this->loadConfig();
        $databaseSettings    = $this->configuration['database'];

        self::$container['database'] = (new $databaseSettings['driver'])::connect(
            $databaseSettings['host'],
            $databaseSettings['port'],
            $databaseSettings['database'],
            $databaseSettings['user'],
            $databaseSettings['password']
        );

        $mailerSettings = $this->configuration['mailer'];

        self::$container['mailer'] = new $mailerSettings['driver'](
            $mailerSettings['host'],
            $mailerSettings['port'],
            $mailerSettings['user'],
            $mailerSettings['password'],
            $mailerSettings['encryption']
        );
    }

    /**
     * Main executable method
     */
    public function exec()
    {
        $trackers = $this->getTrackers();

        foreach ($trackers as $tracker) {
            (new $tracker)->handle();
        }

        echo "Done!\n";
    }

    /**
     * @param string $abstract
     * @return mixed
     * @throws Exception
     */
    public static function getAbstract(string $abstract)
    {
        if (!array_key_exists($abstract, self::$container)) {
            throw new Exception('Abstract in application is not found');
        }

        return self::$container[$abstract];
    }

    /**
     * @return array
     */
    protected function loadConfig(): array
    {
        return require_once config_path('app.php');
    }

    /**
     * @return array
     */
    protected function getTrackers(): array
    {
        return require_once config_path('trackers.php');
    }
}