<?php

namespace App\Mail\Templates;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

abstract class EmailSendTemplate
{
    abstract function send();

    public function __construct(
        protected MailerInterface $mailer,
        protected string          $userEmail = '',
        protected string          $subject = ''
    ) {
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return Email
     */
    protected function prepareEmail(): Email
    {
        return (new Email())
            ->from('no-reply@gmail.com')
            ->to($this->userEmail)
            ->priority(Email::PRIORITY_HIGH)
            ->subject($this->subject);
    }
}
