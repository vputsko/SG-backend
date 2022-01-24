<?php

namespace Tests\Unit\Controllers;

use App\Controllers\LoginController;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Middlewares\Utils\HttpErrorException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class LoginControllerTest extends TestCase
{

    public function testCredentials(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $loginController = new LoginController($userRepository);

        $request = $this->createMock(SymfonyRequest::class);
        $request->expects($this->once())
            ->method('get')
            ->with($this->equalTo('email'))
            ->willReturn('first@example.com');

        $setRequestClosure = function () use ($request){
            $this->request = $request;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($loginController, get_class($loginController));
        $doSetRequestClosure();

        $loginController->credentials();

    }

    public function testValidateLogin(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $loginController = new LoginController($userRepository);

        $request = $this->createMock(SymfonyRequest::class);
        $request->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                [$this->equalTo('email')],
                [$this->equalTo('password')],
            )
            ->willReturnOnConsecutiveCalls('first@example.com', 'password');

        $setRequestClosure = function () use ($request){
            $this->request = $request;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($loginController, get_class($loginController));
        $doSetRequestClosure();

        $loginController->validateLogin();
    }

    public function testValidateLoginEmailAbsent(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $loginController = new LoginController($userRepository);

        $request = $this->createMock(SymfonyRequest::class);
        $request->expects($this->exactly(1))
            ->method('get')
            ->withConsecutive(
                [$this->equalTo('email')],
                [$this->equalTo('password')],
            )
            ->willReturnOnConsecutiveCalls(null, 'password');

        $setRequestClosure = function () use ($request){
            $this->request = $request;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($loginController, get_class($loginController));
        $doSetRequestClosure();

        $this->expectException(HttpErrorException::class);

        $loginController->validateLogin();
    }

    public function testValidateLoginPasswordAbsent(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $loginController = new LoginController($userRepository);

        $request = $this->createMock(SymfonyRequest::class);
        $request->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                [$this->equalTo('email')],
                [$this->equalTo('password')],
            )
            ->willReturnOnConsecutiveCalls('first@example.com', null);

        $setRequestClosure = function () use ($request){
            $this->request = $request;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($loginController, get_class($loginController));
        $doSetRequestClosure();

        $this->expectException(HttpErrorException::class);

        $loginController->validateLogin();
    }

    public function testAuthenticated(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $loginController = new LoginController($userRepository);

        $request = $this->createMock(SymfonyRequest::class);
        $request->expects($this->once())
            ->method('get')
            ->with($this->equalTo('password'))
            ->willReturn('password');

        $setRequestClosure = function () use ($request){
            $this->request = $request;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($loginController, get_class($loginController));
        $doSetRequestClosure();

        $user = new User;
        $user->setName('First');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

        $loginController->authenticated($user);
    }

    public function testAuthenticatedPasswordWrong(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $loginController = new LoginController($userRepository);

        $request = $this->createMock(SymfonyRequest::class);
        $request->expects($this->once())
            ->method('get')
            ->with($this->equalTo('password'))
            ->willReturn('wrong_password');

        $setRequestClosure = function () use ($request){
            $this->request = $request;
        };

        $doSetRequestClosure = $setRequestClosure->bindTo($loginController, get_class($loginController));
        $doSetRequestClosure();

        $user = new User;
        $user->setName('First');
        $user->setEmail('first@example.com');
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT));

        $this->expectException(HttpErrorException::class);
        $loginController->authenticated($user);
    }

}
