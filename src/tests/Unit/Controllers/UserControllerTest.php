<?php

namespace Tests\Unit\Controllers;

use App\Controllers\UserController;
use App\Repositories\UserRepositoryInterface;
use Tests\TestCase;

require_once __DIR__.'/../../../app/helpers.php';

class UserControllerTest extends TestCase
{

    public function testShowUsers(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $wilReturn = array(
            [
                'id' => 1,
                'name' => "First",
                'email' => 'first@example.com'
            ],
            [
                'id' => 2,
                'name' => "Second",
                'email' => 'second@example.com'
            ]
        );

        $userRepository->expects($this->once())
            ->method('getUsers')
            ->willReturn($wilReturn);

        $userController = new UserController($userRepository);
        $response = $userController->showUsers();
        self::assertSame(['data' => $wilReturn], json_decode($response->getBody()->getContents(),true));
    }

    /*public function testShowUser()
    {

    }*/

}
