<?php

declare(strict_types = 1);

require __DIR__ . '/bootstrap/app.php';

use App\Kernel\Application;
use Doctrine\ORM\EntityManagerInterface;

$container = Application::buildContainer();
$entityManager = $container->get(EntityManagerInterface::class);

$newUserName = $argv[1];
$newUserEmail = $argv[2];

$user = new App\Models\User();
$user->setName($newUserName);
$user->setEmail($newUserEmail);
$user->setPassword(password_hash('password', PASSWORD_BCRYPT));

$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID " . $user->getId() . "\n";
