<?php

declare(strict_types = 1);

$container = require __DIR__ . "/bootstrap/app.php";

$app = new Silly\Application();

$app->useContainer($container, true, true);

$app->command('info [name]', 'App\Command\InfoCommand')->defaults(['name' => 'all']);
$app->command('get_user [user_id]', 'App\Command\GetUserCommand');
$app->command('create_queues_doctrine_schema', 'App\Command\CreateQueuesDoctrineSchemaCommand');
$app->command('consume [queue]', 'App\Command\MainConsumeCommand');

$app->setDefaultCommand('info');

$app->run();