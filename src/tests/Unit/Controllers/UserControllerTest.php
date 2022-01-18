<?php

namespace Tests\Unit\Controllers;

use App\Controllers\UserController;
use App\Repositories\UserRepositoryInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Tests\TestCase;

require_once __DIR__.'/../../../app/helpers.php';

class UserControllerTest extends TestCase
{

    public function testShowUsers(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $messageBus = $this->createMock(MessageBusInterface::class);

        $userRepository->expects($this->once())
            ->method('getUsers')
            ->willReturn([]);

        $userController = new UserController($userRepository, $messageBus);
        $response = $userController->showUsers();
    }

    /*public function testShowUser()
    {

    }*/

}
