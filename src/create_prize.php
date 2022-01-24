<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap/app.php';

use App\Kernel\Application;
use Doctrine\ORM\EntityManagerInterface;

$container = Application::buildContainer();
$entityManager = $container->get(EntityManagerInterface::class);

$newPrizeTitle = $argv[1];
$newMaxAmount = $argv[2];

$prize = new App\Models\Prize();
$prize->setTitle($newPrizeTitle);
$prize->setMaxAmount((int) $newMaxAmount);

$entityManager->persist($prize);
$entityManager->flush();

echo "Created Prize with ID " . $prize->getId() . "\n";
