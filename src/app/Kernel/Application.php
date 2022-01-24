<?php

declare(strict_types = 1);

namespace App\Kernel;

use DI\Container;
use DI\ContainerBuilder;
use FastRoute\RouteCollector;
use Middlewares\ErrorHandler;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\HttpErrorException;
use Psr\Container\ContainerInterface;
use FastRoute\Dispatcher\GroupCountBased;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Tuupola\Middleware\JwtAuthentication;
use Middlewares\Emitter;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Laminas\Diactoros\ServerRequestFactory;

class Application
{

    protected bool $development;
    protected ContainerInterface $container;
    protected GroupCountBased $router;
    protected RequestHandlerInterface $middleware;

    public function __construct()
    {
        $this->development = '1' === getenv('LOCAL');
        $this->container = static::buildContainer();
        $this->router = $this->buildRouter($this->routeCollection());
        $this->middleware = $this->buildMiddleware();
    }

    public function run(): void
    {
        $this->middleware->dispatch(ServerRequestFactory::fromGlobals());
    }

    public static function buildContainer(): Container
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions(__DIR__ . '/../../config/di.php');

        if ('1' === getenv('LOCAL')) {
            //Development injection
            $containerBuilder->addDefinitions(__DIR__ . '/../../config/di.dev.php');
        } else {
            //Caching for production
            $containerBuilder->enableCompilation('/var/tmp/cache');
            $containerBuilder->enableDefinitionCache();
        }

        return $containerBuilder->build();
    }

    /**
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function routeCollection(): array
    {
        $routers = require __DIR__ . '/../../config/routers.php';

        $routeCollector = $this->container->get(RouteCollector::class);

        foreach ($routers as $router) {
            $routeCollector->addRoute(...$router);
        }

        return $routeCollector->getData();
    }

    /**
     * @param  array  $routeCollection
     * @return GroupCountBased
     */
    protected function buildRouter(array $routeCollection): GroupCountBased
    {
        return new GroupCountBased($routeCollection);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getJwtAuthentication(): MiddlewareInterface
    {
        $jwtConfig = [
            "error" => function ($response, $arguments) {
                throw HttpErrorException::create(401);
            },
            "logger" => $this->container->get(LoggerInterface::class),
        ];
        $jwtConfig += require __DIR__ . '/../../config/jwt.php';

        return new JwtAuthentication($jwtConfig);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function buildMiddleware(): RequestHandlerInterface
    {
        return new Dispatcher([
            $this->container->get(Emitter::class),

            $this->container->get(ErrorHandler::class),

            $this->getJwtAuthentication(),

            new FastRoute($this->router),

            new RequestHandler($this->container),
        ]);
    }

}