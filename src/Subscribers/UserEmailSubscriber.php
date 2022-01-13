<?php

namespace App\Subscribers;

use App\Events\InvitationSentEvent;
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
            InvitationSentEvent::NAME => 'onInvitationCreated',
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

    public function onInvitationCreated(InvitationSentEvent $event)
    {
        $invitedByEmail = $event->getInvitation()->getInvitedBy()->getEmail();
        $invitedEmail = $event->getInvitation()->getInvitedUser()->getEmail();

        $message = "<p>Dear user, You have just been invited by $invitedByEmail</p>";
        $mailer = new HtmlTextEmailService($this->mailer, $message, $invitedEmail, 'Invitation received');
        $mailer->send();
    }
}
