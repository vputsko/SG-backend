<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap/app.php';

use App\Kernel\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;


$container = Application::buildContainer();
$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
