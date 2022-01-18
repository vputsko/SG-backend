<?php

declare(strict_types = 1);

namespace App\Controllers\Handles;

use App\Messages\Notification;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class NotificationHandler implements MessageHandlerInterface
{

    private UserRepositoryInterface $userRepository;

    protected MailerInterface $mailer;

    /**
     * @param  UserRepositoryInterface  $userRepository
     * @param  MailerInterface  $mailer
     */
    public function __construct(UserRepositoryInterface $userRepository, MailerInterface $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * @param  Notification  $message
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(Notification $message): void
    {

        $userId = $message->getContent()['id'];

        $user = $this->userRepository->getUser($userId);

        $email = (new Email())
            ->from(getenv('MAILGUN_EMAIL'))
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
    }
    
}