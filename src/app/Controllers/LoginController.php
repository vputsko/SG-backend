<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Support\Traits\RequestFormatter;
use App\Support\Traits\Serialized;
use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\HttpErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PsrJwt\Factory;

class LoginController
{
    use Serialized, RequestFormatter;

    /** @var UserRepository  */
    protected UserRepository $userRepository;

    /**
     * @param  UserRepository  $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  ServerRequestInterface  $serverRequest
     * @return ResponseInterface
     * @throws HttpErrorException
     * @throws \ReallySimpleJWT\Exception\ValidateException
     */
    public function login(ServerRequestInterface $serverRequest): ResponseInterface
    {

        $this->getRequest($serverRequest);

        $user = $this->userRepository->getUsersBy($this->credentials());

        $this->authenticated($user);

        return response($this->toJson([
            "message" => "Successful login.",
            "token" => $this->generateToken($user),
        ]));
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    protected function credentials(): array
    {
        return array(
            'email' => $this->request->get('email')
            );
    }

    /**
     * Validate the user login request.
     *
     * @return void
     * @throws HttpErrorException
     */
    protected function validateLogin()
    {
        $email = $this->request->get('email');

        if (! $email) {
            throw HttpErrorException::create(400, ['problem' => 'email required']);
        }

        $password = $this->request->get('password');

        if (! $password) {
            throw HttpErrorException::create(400, ['problem' => 'password required']);
        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  User  $user
     * @return void
     * @throws HttpErrorException
     */
    protected function authenticated($user): void
    {
        if (!password_verify($this->request->get('password'),$user->getPassword())) {
            throw HttpErrorException::create(401, ['problem' => 'wrong password']);
        }
    }

    /**
     * Generate JWT token
     *
     * @param User $user
     * @return string
     * @throws \ReallySimpleJWT\Exception\ValidateException
     */
    protected function generateToken(User $user): string
    {
        $factory = new \PsrJwt\Factory\Jwt();

        $builder = $factory->builder();

        $token = $builder->setSecret(getenv('JWT_SECRET'))
            ->setPayloadClaim('uid', $user->getId())
            ->build();

        return $token->getToken();
    }

}

