<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Middlewares\Utils\HttpErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PsrJwt\Factory\Jwt;

class LoginController extends Controller
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle a login request to the application.
     *
     * @throws HttpErrorException
     * @throws \ReallySimpleJWT\Exception\ValidateException
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {

        $this->setRequest($serverRequest);

        $user = $this->userRepository->getUsersBy($this->credentials());

        $this->authenticated($user);

        return $this->createResponse([
            "message" => "Successful login.",
            "token" => $this->generateToken($user),
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    public function credentials(): array
    {
        return array(
            'email' => $this->getRequest()->get('email')
            );
    }

    /**
     * Validate the user login request.
     *
     * @throws HttpErrorException
     */
    public function validateLogin(): void
    {
        $email = $this->getRequest()->get('email');

        if (! $email) {
            throw HttpErrorException::create(400, ['problem' => 'email required']);
        }

        $password = $this->getRequest()->get('password');

        if (! $password) {
            throw HttpErrorException::create(400, ['problem' => 'password required']);
        }
    }

    /**
     * The user has been authenticated.
     *
     * @throws HttpErrorException
     */
    public function authenticated(User $user): void
    {
        if (!password_verify($this->getRequest()->get('password'),$user->getPassword())) {
            throw HttpErrorException::create(401, ['problem' => 'wrong password']);
        }
    }

    /**
     * Generate JWT token
     *
     * @throws \ReallySimpleJWT\Exception\ValidateException
     */
    public function generateToken(User $user): string
    {
        $factory = new Jwt;

        $builder = $factory->builder();

        $token = $builder->setSecret(getenv('JWT_SECRET'))
            ->setPayloadClaim('uid', $user->getId())
            ->build();

        return $token->getToken();
    }

}

