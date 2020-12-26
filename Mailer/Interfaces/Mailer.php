<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 10:27 PM
 */

namespace Mailer\Interfaces;


/**
 * Interface Mailer
 * @package Mailer\Interfaces
 */
interface Mailer
{
    /**
     * @param string $to
     * @param string $content
     * @param string $subject
     * @param string $fromAddress
     * @param string $fromName
     * @return mixed
     */
    public function send(string $to, string $content, string $subject, string $fromAddress, string $fromName);
}
