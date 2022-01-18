<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Messages\Notification;
use App\Repositories\UserRepositoryInterface;
use Middlewares\Utils\HttpErrorException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserController extends Controller
{

    protected UserRepositoryInterface $userRepository;

    protected EventDispatcher $dispatcher;

    protected MessageBusInterface $bus;

    protected ContainerInterface $receiverLocator;

    protected ?MailerInterface $mailer;

    protected ?LoggerInterface $logger;

    /**
     * @param  UserRepositoryInterface  $userRepository
     * @param  MessageBusInterface  $bus
     * @param  LoggerInterface  $logger
     * @param  MailerInterface  $mailer
     */
    public function __construct(UserRepositoryInterface $userRepository, MessageBusInterface $bus, ?LoggerInterface $logger = null, ?MailerInterface $mailer = null)
    {
        $this->userRepository = $userRepository;
        $this->bus = $bus;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * Get user's list
     *
     * @return ResponseInterface
     */
    public function showUsers(): ResponseInterface
    {
        $users = $this->userRepository->getUsers();

        return $this->createResponse(['data' => $users]);
    }

    /**
     * Get user by id
     *
     * @param  ServerRequestInterface  $serverRequest
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function showUser(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $this->setRequest($serverRequest);

        $id = $this->getRequest()->get('id');

        if (! $id) {
            throw HttpErrorException::create(400);
        }

        $user = $this->userRepository->getUser($id);

        /*$email = (new Email())
            ->from(getenv('MAILGUN_EMAIL'))
            ->to($user->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);*/

        $this->bus->dispatch(new Notification(['id' => $user->getId()]));

        return $this->createResponse($user);
    }

}

