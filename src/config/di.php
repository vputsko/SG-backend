<?php
declare(strict_types = 1);

use App\Models\User;
use App\Repositories\UserRepository;
use Bernard\Driver;
use Bernard\QueueFactory;
use Bernard\Router;
use Bernard\Router\SimpleRouter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use function DI\factory;
use function DI\get;
use function DI\env;
use Bernard\Driver\DoctrineDriver;
use Bernard\Driver\FlatFileDriver;
use Bernard\Serializer\SimpleSerializer as Serializer;
use App\Services\NewsletterMessageHandler;
use Bernard\QueueFactory\PersistentFactory;
use App\Jobs\SendNewsletterJob;

return [

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

    'Driver' => fn (EntityManagerInterface $em) => new DoctrineDriver($em->getConnection()),

    Router::class => fn (SendNewsletterJob $job) => new SimpleRouter(array('SendNewsletter' => $job)),

    QueueFactory::class => fn (EntityManagerInterface $em) => new PersistentFactory(new FlatFileDriver('/var/tmp'), new Serializer()),

    //Response::class =>

    // Bind an interface to an implementation
    //ArticleRepository::class => create(InMemoryArticleRepository::class),

    //Repositories
    UserRepository::class => fn (EntityManagerInterface $em) => $em->getRepository(User::class),
];