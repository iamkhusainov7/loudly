<?php

namespace App\Subscribers;

use App\Events\InvitationEvent;
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
            InvitationEvent::USER_INVITED => 'onInvitationCreated',
            InvitationEvent::USER_CANCELED => 'onInvitationCanceled',
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onRegisterEmail(UserRegisteredEvent $event)
    {
        $message = "<p>Dear user, please confirm your email address by clicking this link: <a href='{$event->getEmailVerificationLink()}'>Verify</a></p>";
        $this->sendEmail( $message, $event->getUser()->getEmail(), 'Email confirmation');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function onInvitationCreated(InvitationEvent $event)
    {
        $invitedByEmail = $event->getInvitation()->getInvitedBy()->getEmail();
        $invitedEmail = $event->getInvitation()->getInvitedUser()->getEmail();

        $message = "<p>Dear user, You have just been invited by $invitedByEmail</p>";
        $this->sendEmail($message, $invitedEmail, 'Invitation received');
    }

    public function onInvitationCanceled(InvitationEvent $event)
    {
        $invitedByEmail = $event->getInvitation()->getInvitedBy()->getEmail();
        $invitedEmail = $event->getInvitation()->getInvitedUser()->getEmail();

        $message = "<p>Dear user, the invitation sent by $invitedByEmail has just been canceled!</p>";
        $this->sendEmail($message, $invitedEmail, 'Invitation canceled');
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function sendEmail(string $message, string $email, string $subject)
    {
        $mailer = new HtmlTextEmailService($this->mailer, $message, $email, $subject);
        $mailer->send();
    }
}
