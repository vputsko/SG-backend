<?php

declare(strict_types = 1);

namespace Tests\Feature\Controllers;

use App\Controllers\UserController;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\HttpErrorException;
use Tests\RefreshesDatabase;
use Tests\TestCase;

require_once __DIR__.'/../../../app/helpers.php';

class UserControllerTest extends TestCase
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

    public function testShowUser()
    {
        $this->createDatabaseSchema();

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $userController = new UserController($this->userRepository);

        $serverRequest = (new ServerRequest())->withQueryParams([
            'id' => '1',
        ]);

        $response = $userController->showUser($serverRequest);

        self::assertSame(200, $response->getStatusCode());

        $responseArray = json_decode($response->getBody()->getContents(),true);
        self::assertArrayNotHasKey('password', $responseArray);
        self::assertArrayNotHasKey('createdAt', $responseArray);
        self::assertArrayNotHasKey('updatedAt', $responseArray);
    }
    
    public function testShowUserIdAbsent()
    {
        $this->createDatabaseSchema();
        
        $userController = new UserController($this->userRepository);

        $serverRequest = (new ServerRequest())->withQueryParams([]);

        $this->expectException(HttpErrorException::class);
        $this->expectExceptionCode(400);
        $userController->showUser($serverRequest);
    }

    public function testShowUsers()
    {
        $this->createDatabaseSchema();

        $user = new User;
        $user->setName('The first');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $this->em->persist($user);
        $this->em->flush();

        $userController = new UserController($this->userRepository);

        $response = $userController->showUsers();

        self::assertSame(200, $response->getStatusCode());

        $responseArray = json_decode($response->getBody()->getContents(),true);
        self::assertArrayHasKey('data', $responseArray);
        foreach ($responseArray['data'] as $user) {
            self::assertArrayNotHasKey('password', $user);
            self::assertArrayNotHasKey('createdAt', $user);
            self::assertArrayNotHasKey('updatedAt', $user);
        }
    }

}
