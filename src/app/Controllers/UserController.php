<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Support\Traits\RequestFormatter;
use App\Support\Traits\Serialized;
use Doctrine\ORM\EntityManagerInterface;
use Middlewares\Utils\HttpErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\QueueFactory;
use Bernard\Envelope;
use Symfony\Component\EventDispatcher\EventDispatcher;
use App\Support\EnvelopeEvent;
use App\Jobs\SendNewsletterJob;

class UserController
{
    use Serialized, RequestFormatter;

    /** @var UserRepository  */
    protected UserRepository $userRepository;

    protected QueueFactory $queues;

    protected EventDispatcher $dispatcher;

    protected SendNewsletterJob $job;

    /**
     * @param  UserRepository  $userRepository
     */
    public function __construct(UserRepository $userRepository, QueueFactory $queues, EventDispatcher $dispatcher, SendNewsletterJob $job)
    {
        $this->userRepository = $userRepository;
        $this->queues = $queues;
        $this->dispatcher = $dispatcher;
        $this->job = $job;
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

        $this->job->dispatch([
            'user_id' => $user->getId(),
        ]);

        /*$message = new SendNewsletterJob('SendNewsletter', [
            'user_id' => $user->getId(),
        ]);
        $queue = $this->queues->create('send-newsletter');
        $queue->enqueue($envelope = new Envelope($message));

        $this->dispatcher->dispatch(new EnvelopeEvent($envelope, $queue), 'bernard.produce');*/

        return response($this->toJson($user));
    }

}

