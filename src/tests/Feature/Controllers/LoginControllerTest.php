<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers;

use App\Controllers\LoginController;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\HttpErrorException;
use Tests\RefreshesDatabase;
use Tests\TestCase;

require_once __DIR__.'/../../../app/helpers.php';

class LoginControllerTest extends TestCase
{

    use RefreshesDatabase;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function testLogin()
    {
        $this->createDatabaseSchema();

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $serverRequest = (new ServerRequest())->withQueryParams([
            'email' => 'first@example.com',
            'password' => 'password',
        ]);

        $loginController = new LoginController($this->userRepository);

        $response = $loginController($serverRequest);

        self::assertSame(200, $response->getStatusCode());
        self::assertJsonStringEqualsJsonString('{"message":"Successful login.","token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOjF9.dfLsC_HOSGX6bkWmUXfFJYL6peS_4iV-IfH40YnCm5w"}', $response->getBody()->getContents());
    }

    public function testLoginPasswordWrong()
    {
        $this->createDatabaseSchema();

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $serverRequest = (new ServerRequest())->withQueryParams([
            'email' => 'first@example.com',
            'password' => 'wrong_password',
        ]);

        $loginController = new LoginController($this->userRepository);

        $this->expectException(HttpErrorException::class);
        $response = $loginController($serverRequest);
    }
    
    public function testLoginUserNotFound()
    {
        $this->createDatabaseSchema();

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $serverRequest = (new ServerRequest())->withQueryParams([
            'email' => 'second@example.com',
            'password' => 'password',
        ]);

        $loginController = new LoginController($this->userRepository);

        $this->expectException(EntityNotFoundException::class);
        $response = $loginController($serverRequest);
    }

}