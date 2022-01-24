<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Repositories\UserRepositoryInterface;
use Middlewares\Utils\HttpErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get user's list
     */
    public function showUsers(): ResponseInterface
    {
        $users = $this->userRepository->getUsers();

        return $this->createResponse(['data' => $users], ['id', 'name', 'email']);
    }

    /**
     * Get user by id
     *
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

        return $this->createResponse($user, ['id', 'name', 'email']);
    }

    /**
     * Get current authenticated user
     * 
     * @param  ServerRequestInterface  $serverRequest
     * @return ResponseInterface
     * @throws HttpErrorException
     */
    public function showCurrentUser(ServerRequestInterface $serverRequest): ResponseInterface
    {

        $this->setRequest($serverRequest);

        if (! $this->getRequest()->get('token')) {
            throw HttpErrorException::create(401);
        }

        $serverRequest = $serverRequest->withAttribute('id', $this->getRequest()->get('token')['uid']);

        return $this->showUser($serverRequest);
    }

}

