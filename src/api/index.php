<?php 

declare(strict_types = 1);

use App\Controllers\UserController;
use App\Controllers\LoginController;
use FastRoute\RouteCollector;
use Middlewares\ErrorFormatter;
use Middlewares\ErrorHandler;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\HttpErrorException;
use Symfony\Component\Messenger\RoutableMessageBus;

$container = require __DIR__ . "/../bootstrap/app.php";

require_once __DIR__ . "/../app/helpers.php";

$dispatcher = FastRoute\simpleDispatcher(static function (RouteCollector $r): void {
    $r->addRoute(['GET', 'POST'], '/login', [LoginController::class, 'login']);

    $r->addRoute(['POST', 'GET'], '/users', [UserController::class, 'showUsers']);
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/user/{id:\d+}', [UserController::class, 'showUser']);
    //$r->addRoute('GET', '/article/{id}', ['SuperBlog\Controller\ArticleController', 'show']);
});

$dispatcher = new Dispatcher([
    new Middlewares\Emitter(),

    new ErrorHandler([
        new ErrorFormatter\JsonFormatter(),
    ]),

    new Tuupola\Middleware\JwtAuthentication([
        "secret" => getenv('JWT_SECRET'),
        "ignore" => ["/login", "/user"],
        "error" => function ($response, $arguments) {
            throw HttpErrorException::create(401);
        }
    ]),

    new Middlewares\FastRoute($dispatcher),
    new Middlewares\RequestHandler($container),
]);

$dispatcher->dispatch(Laminas\Diactoros\ServerRequestFactory::fromGlobals());
