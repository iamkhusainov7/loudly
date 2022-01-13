<?php

namespace App\Subscribers;

use App\Events\UserRegisteredEvent;
use App\Mail\HtmlTextEmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class UserEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onRegisterEmail',
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onRegisterEmail(UserRegisteredEvent $event)
    {
        $message = "<p>Dear user, please confirm your email address by clicking this link: <a href='{$event->getEmailVerificationLink()}'>Verify</a></p>";
        $mailer = new HtmlTextEmailService($this->mailer, $message, $event->getUser()->getEmail(), 'Email confirmation');
        $mailer->send();
    }
}
