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
 * Class Eldorado
 * @package Tracker
 */
class Eldorado extends AbstractTracker implements Tracker
{
    /**
     * Base URL
     */
    const BASE_URL = 'https://api.retailrocket.net';

    /**
     * API Endpoint
     */
    const API_ENDPOINT = 'api/1.0/partner/5ba1feda97a5252320437f20/items';

    /**
     * @var array
     */
    private $itemIds = [
        '71554192' => 'https://www.eldorado.ru/cat/detail/igrovaya-pristavka-playstation-5-digital-edition/',
        '71539984' => 'https://www.eldorado.ru/cat/detail/igrovaya-pristavka-sony-playstation-5/',
//        '71564562' => 'https://www.eldorado.ru/cat/detail/ultra-hd-4k-led-televizor-43-hi-vhix-43u169msa/',
//        '11111111' => 'https://www.eldorado.ru/cat/detail/igrovaya-pristavka-sony-playstation-511/',
    ];

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        foreach ($this->itemIds as $key => $value) {
            $this->sendRequest($key, $value);
        }
    }

    /**
     * @param string $itemId
     * @param string $uri
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function sendRequest(string $itemId, string $uri)
    {
        $link = $this->generateApiRequestLink($itemId);

        $res = $this->trackResource($link);

        if ($res === null) {
            return;
        }

        $parsedResponse = $this->parseContent($res, true);

        if (count($parsedResponse) === 0) {
            echo "Empty data while fetching data from URI $link";
            Logger::log("Empty data while fetching data from URI $link", 'error', $link);

            return;
        }

        if (!array_key_exists('IsAvailable', $parsedResponse[0])) {
            echo "Unexpected format data URI $link";
            Logger::log("Unexpected format data URI $link", 'error', $link);

            return;
        }

        if ($parsedResponse[0]['IsAvailable'] === true) {
            echo "Goods has been found $uri\n";
            Logger::log("Goods has been found $uri", 'success', $uri);

            $this->notifyRecipients($uri);
        }
    }

    /**
     * @param string $itemId
     * @return string
     */
    private function generateApiRequestLink(string $itemId): string
    {
        return rtrim(self::BASE_URL, '/') . '/' . rtrim(self::API_ENDPOINT, '/') . '?itemsIds=' . $itemId . '&format=json';
    }
}