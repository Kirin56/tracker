<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 6:35 PM
 */

namespace Tracker;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Log\Logger;
use Psr\Http\Message\ResponseInterface;
use Exception;

/**
 * Class AbstractTracker
 * @package Tracker
 */
abstract class AbstractTracker
{
    /**
     * @var mixed
     */
    protected $recipients;

    /**
     * @var Client
     */
    protected $guzzle;

    /**
     * AbstractTracker constructor.
     */
    public function __construct()
    {
        $this->recipients = require config_path('recipients.php');
        $this->guzzle     = new Client;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function trackResource(string $uri, string $method = 'GET', array $options = [])
    {
        try {
            echo "Fetching data from URI $uri\n";

            $response = $this->guzzle->request($method, $uri, $options);

            echo "Data from URI  $uri has been fetched\n";
        } catch (RequestException $e) {
            $this->handleErrors($e, $uri);

            return null;
        }

        return $response;
    }

    /**
     * @param RequestException $e
     * @param string $uri
     * @throws Exception
     */
    protected function handleErrors(RequestException $e, string $uri): void
    {
        echo 'Something went wrong: ' . $e->getMessage();
        Logger::log('Something went wrong: ' . $e->getMessage(), 'error', $uri);

        if (!$e->hasResponse()) {
            Logger::log('URI fetch error: ' . $e->getMessage(), 'error', $uri);
        }

        $results = $this->parseContent($e->getResponse());

        if ($results === null) {
            Logger::log('Empty response from recourse', 'error', $uri);
        }
    }

    /**
     * @param ResponseInterface $response
     * @param bool $isJSON
     * @return mixed|string|null
     */
    protected function parseContent(ResponseInterface $response, bool $isJSON = false)
    {
        if ($response->getBody() === null) {
            return null;
        }

        if ($isJSON === true) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param string $link
     * @return void
     * @throws \Exception
     */
    protected function notifyRecipients(string $link): void
    {
        $mailer = app('mailer');

        foreach ($this->recipients as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    echo "Sending email to $email...\n";

                    $mailer->send($email, $this->createMessage($link), 'New shipment!');

                    echo "Mail to $email sent\n";
                    Logger::log("Mail successfully has been sent to $email", 'success');
                } catch (Exception $e) {
                    Logger::log("Error while sending email to $email: " . $e->getMessage(), 'error');
                }
            }
        }
    }

    /**
     * @param string $link
     * @return string
     */
    protected function createMessage(string $link): string
    {
        $message = file_get_contents(template_path('mail/main.html'));

        $message = str_replace('{{@date}}', date('d.m.Y H:i:s'), $message);
        $message = str_replace('{{@link}}', $link, $message);
        $message = str_replace('{{@link-caption}}', $link, $message);

        return $message;
    }
}