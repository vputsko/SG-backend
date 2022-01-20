<?php

declare(strict_types = 1);

$container = require __DIR__ . "/bootstrap/app.php";

$app = new Silly\Application();

$app->useContainer($container, true, true);

$app->command('consume [receivers] [-l|--limit=] [-f|--failure-limit=] [-m|--memory-limit=] [-t|--time-limit=] [-b|--bus] [--sleep] [--queues] [--no-reset]', ['App\Command\SymfonyConsumeMessagesCommand', 'execute'])->defaults([
    'receivers' => ['async'],
    'limit' => 1,
    'failure-limit' => 0,
    'memory-limit' => 0,
    'time-limit' => 1,
]);
$app->command('send_money [limit]', ['App\Command\SendMoneyCommand', 'execute'])->defaults([
    'limit' => 0,
]);

$app->setDefaultCommand('consume');

$app->run();