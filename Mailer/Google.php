<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 10:27 PM
 */

namespace Mailer;


use Mailer\Interfaces\Mailer;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Google
 * @package Mailer
 */
class Google implements Mailer
{
    /**
     * @var PHPMailer
     */
    private $mail;

    /**
     * Google constructor.
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $password
     * @param string $encryption
     */
    public function __construct(string $host, string $port, string $user, string $password, string $encryption = 'tls')
    {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->SMTPAuth   = true;
        $this->mail->Host       = $host;
        $this->mail->Port       = $port;
        $this->mail->Username   = $user;
        $this->mail->Password   = $password;
        $this->mail->SMTPSecure = $encryption;
    }

    /**
     * @param string $to
     * @param string $content
     * @param string $subject
     * @param string $fromAddress
     * @param string $fromName
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function send(string $to, string $content, string $subject, string $fromAddress = 'trackingsender@gmail.com', string $fromName = 'Track System')
    {
        $this->mail->setFrom($fromAddress, $fromName);
        $this->mail->addAddress($to);

        $this->mail->isHTML();
        $this->mail->Subject = $subject;
        $this->mail->Body    = $content;

        $this->mail->send();

        $this->mail->clearAddresses();
    }
}