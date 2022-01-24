<?php

declare(strict_types = 1);

use App\Command\SendMoneyCommand;
use App\Command\SymfonyConsumeMessagesCommand;
use App\Controllers\Handles\SendMoneyHandler;
use App\Controllers\LoginController;
use App\Controllers\UserController;
use App\Messages\SendMoneyMessage;
use App\Models\Prize;
use App\Models\User;
use App\Repositories\Doctrine\PaymentsDoctrineRepository;
use App\Repositories\PaymentsRepositoryInterface;
use App\Repositories\PrizeRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\BankApiFactory;
use App\Services\MonetaBankApi;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use Middlewares\Emitter;
use Middlewares\ErrorFormatter\JsonFormatter;
use Middlewares\ErrorHandler;
use Monolog\Handler\LogglyHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Bridge\Mailgun\Transport\MailgunTransportFactory;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;
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
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Mime\Address;
use function DI\autowire;
use function DI\create;
use function DI\env;
use function DI\factory;
use function DI\get;

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
    'doctrine.config' => Setup::createXMLMetadataConfiguration(array(__DIR__."/xml"), false, '/var/tmp/proxy', null),
    Connection::class => fn (EntityManagerInterface $em) => new Connection([], $em->getConnection()),

    //Routers
    Std::class => autowire(),
    GroupCountBased::class => autowire(),
    RouteCollector::class => autowire()->constructor(get(Std::class), get(GroupCountBased::class)),
    
    //Transport
    'async' => create(DoctrineTransport::class)->constructor(get(Connection::class), get(SerializerInterface::class)),

    //Senders
    SendersLocatorInterface::class => create(SendersLocator::class)->constructor([
        "*" => ['async'],
    ], get(ContainerInterface::class)),
    SendMessageMiddleware::class => create()->constructor(get(SendersLocatorInterface::class), get(EventDispatcherInterface::class))->method('setLogger', get(LoggerInterface::class)),

    //Handlers
    SendMoneyHandler::class => create()->constructor(get(PaymentsRepositoryInterface::class), get(BankApiFactory::class)),
    HandlersLocatorInterface::class => create(HandlersLocator::class)
        ->constructor([
            SendMoneyMessage::class => [
                get(SendMoneyHandler::class)
            ],
        ]),
    HandleMessageMiddleware::class => create()
        ->constructor(get(HandlersLocatorInterface::class))
        ->method('setLogger', get(LoggerInterface::class)),
    Emitter::class => autowire(),
    JsonFormatter::class => autowire(),
    ErrorHandler::class => autowire()->constructor([get(JsonFormatter::class)]),

    //Buses
    MessageBusInterface::class => create(MessageBus::class)->constructor([
        get(SendMessageMiddleware::class), 
        get(HandleMessageMiddleware::class),
    ]),
    RoutableMessageBus::class => create()->constructor(get(ContainerInterface::class), get(MessageBusInterface::class)),

    //Command
    SymfonyConsumeMessagesCommand::class => autowire()->constructorParameter('logger', get(LoggerInterface::class)),
    SendMoneyCommand::class => autowire(),

    //Mailer
    Dsn::class => create()->constructor(
        'mailgun+smtps',
        'smtp.mailgun.org',
        env('MAILGUN_EMAIL'),
        env('MAILGUN_TOKEN'),
        587
    ),
    MailgunTransportFactory::class => autowire()->constructorParameter('logger', get(LoggerInterface::class)),
    TransportInterface::class => factory([MailgunTransportFactory::class, 'create'])->parameter('dns', get(Dsn::class)),
    MailerInterface::class => create(Mailer::class)->constructor(get(TransportInterface::class)),
    Address::class => create()->constructor(env('MAILGUN_EMAIL')),
    Envelope::class => create()->constructor(get(Address::class), [get(Address::class)]),

    //Misc
    SerializerInterface::class => create(Serializer::class),
    EventDispatcherInterface::class => create(EventDispatcher::class),
    LogglyHandler::class => create()->constructor(env('LOGGLY_TOKEN')),
    PsrLogMessageProcessor::class => autowire(),
    LoggerInterface::class => create(NullLogger::class),

    //Repositories
    UserRepositoryInterface::class => fn (EntityManagerInterface $em) => $em->getRepository(User::class),
    PrizeRepositoryInterface::class => fn (EntityManagerInterface $em) => $em->getRepository(Prize::class),
    PaymentsRepositoryInterface::class => create(PaymentsDoctrineRepository::class),
    
    //Services
    BankApiFactory::class => autowire()->constructor([
        MonetaBankApi::class => get(MonetaBankApi::class)
    ]),
    MonetaBankApi::class => autowire(),

    //Controllers
    UserController::class => autowire(),
    LoginController::class => autowire(),
    
];