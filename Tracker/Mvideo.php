<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 11:44 PM
 */

namespace Tracker;


use Log\Logger;
use Tracker\Interfaces\Tracker;

/**
 * Class Mvideo
 * @package Tracker
 */
class Mvideo extends AbstractTracker implements Tracker
{
    /**
     * Base URL
     */
    const BASE_URL = 'https://www.mvideo.ru';

    /**
     * @var array
     */
    private $trackingPages = [
        'products/igrovaya-konsol-sony-playstation-5-40073270',
        'products/igrovaya-konsol-sony-playstation-5-digital-edition-40074203',
        'products/smartfon-huawei-p40-lite-e-nfc-midnight-black-art-l29n-30050496',
        'products/smartfon-huawei-p40-lite-e-nfc-midnight-black-art-l29n-30050496sss',
    ];

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        foreach ($this->trackingPages as $page) {
            $this->sendRequest($page);
        }
    }

    /**
     * @param string $page
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendRequest(string $page)
    {
        $curLink = rtrim(self::BASE_URL, '/') . '/' . $page;


        $res = $this->trackResource($curLink);

        if ($res === null) {
            return;
        }

        $parsedResponse = $this->parseContent($res);

        if ($parsedResponse !== null && strpos($parsedResponse, 'Товар распродан') === false) {
            echo "Goods has been found $curLink\n";
            Logger::log("Goods has been found $curLink", 'success', $curLink);

            $this->notifyRecipients($curLink);
        }
    }
}