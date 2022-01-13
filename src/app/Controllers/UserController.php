<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Jobs\SendNewsletterJob;
use App\Messages\Notification;
use App\Repositories\UserRepository;
use App\Support\Traits\RequestFormatter;
use App\Support\Traits\Serialized;
use Bernard\QueueFactory;
use Middlewares\Utils\HttpErrorException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\MessageBusInterface;

class UserController
{
    use Serialized, RequestFormatter;

    /** @var UserRepository  */
    protected UserRepository $userRepository;

    protected QueueFactory $queues;

    protected EventDispatcher $dispatcher;

    protected SendNewsletterJob $job;

    protected MessageBusInterface $bus;

    protected ContainerInterface $receiverLocator;

    /**
     * @param  UserRepository  $userRepository
     */
    public function __construct(UserRepository $userRepository, MessageBusInterface $bus)
    {
        $this->userRepository = $userRepository;
        $this->bus = $bus;
    }

    /**
     * Get user's list
     *
     * @return ResponseInterface
     */
    public function showUsers(): ResponseInterface
    {

        $users = $this->userRepository->getUsers();

        return response($this->toJson($users));
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
        $this->getRequest($serverRequest);

        $id = $this->request->get('id');

        if (! $id) {
            throw HttpErrorException::create(400);
        }

        $user = $this->userRepository->getUser($id);

        $this->bus->dispatch(new Notification('Look! I created a message!'));

        return response($this->toJson($user));
    }

}

