<?php

namespace App\Mail;

use App\Mail\Templates\EmailSendTemplate;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class HtmlTextEmailService extends EmailSendTemplate
{
    public function __construct(MailerInterface $mailer, private string $message, string $userEmail = '', string $subject = '')
    {
        parent::__construct($mailer, $userEmail, $subject);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send()
    {
        $message = $this->prepareEmail()
            ->html($this->message);

        $this->mailer->send($message);
    }
}