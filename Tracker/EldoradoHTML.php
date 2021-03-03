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
 * Class EldoradoHTML
 * @package Tracker
 */
class EldoradoHTML extends AbstractTracker implements Tracker
{
    /**
     * Base URL
     */
    const BASE_URL = 'https://www.eldorado.ru';

    /**
     * @var array
     */
    private $trackingPages = [
        'cat/detail/igrovaya-pristavka-playstation-5-digital-edition',
        'cat/detail/igrovaya-pristavka-sony-playstation-5',
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
            echo "They blocked the resource, perhaps, they have something to hide, check it!\n";

            $this->notifyRecipientsBlocking($curLink);

            return;
        }

        $parsedResponse = $this->parseContent($res);

        if ($parsedResponse !== null && strpos($parsedResponse, 'Сообщить о поступлении') === false) {
            echo "Goods has been found $curLink\n";
            Logger::log("Goods has been found $curLink", 'success', $curLink);

            $this->notifyRecipients($curLink, 'New shipment Eldorado!');
        }
    }
}