<?php
declare(strict_types = 1);

use App\Controllers\Handles\NotificationHandler;
use App\Messages\Notification;
use App\Models\User;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\Connection;
use Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransport;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\RoutableMessageBus;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;

use function DI\env;
use function DI\factory;
use function DI\get;
use function DI\create;

return [

    //Doctrine
    EntityManagerInterface::class => factory([EntityManager::class, 'create'])
        ->parameter('connection', get('db.params'))
        ->parameter('config', get('doctrine.config')),

    'db.params' => [
        'driver' => env('DB_DRIVER'),
        'host' => env('DB_HOST'),
        'dbname' => env('DB_DATABASE'),
        'user' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'path' => '/var/tmp/db.sqlite',
    ],

    'doctrine.config' => Setup::createXMLMetadataConfiguration(array(__DIR__."/xml"), (1 == env('LOCAL')), null, null),

    Connection::class => fn (EntityManagerInterface $em) => new Connection([], $em->getConnection()),

    //Transport
    'async' => create(DoctrineTransport::class)->constructor(get(Connection::class), new Serializer()),

    //Senders
    SendersLocatorInterface::class => create(SendersLocator::class)->constructor([
        "*" => ['async'],
    ], get(ContainerInterface::class)),

    SendMessageMiddleware::class => create()->constructor(get(SendersLocatorInterface::class), get(EventDispatcherInterface::class)),

    //Handlers
    HandlersLocatorInterface::class => create(HandlersLocator::class)->constructor([ Notification::class => [new NotificationHandler()] ]),

    HandleMessageMiddleware::class => create()->constructor(get(HandlersLocatorInterface::class)),

    //Bus
    MessageBusInterface::class => fn (SendMessageMiddleware $sendMessageMiddleware, HandleMessageMiddleware $handleMessageMiddleware) => new MessageBus([$sendMessageMiddleware, $handleMessageMiddleware]),

    RoutableMessageBus::class => create()->constructor(get(ContainerInterface::class), get(MessageBusInterface::class)),

    //Misc
    EventDispatcherInterface::class => fn () => new EventDispatcher(),

    //Repositories
    UserRepository::class => fn (EntityManagerInterface $em) => $em->getRepository(User::class),
];