<?php
$entityManager = require_once "./bootstrap/bootstrap_doctrine.php";

$newUserName = $argv[1];
$newUserEmail = $argv[2];

$user = new App\Models\User();
$user->setName($newUserName);
$user->setEmail($newUserEmail);
$user->setPassword(password_hash('password', PASSWORD_BCRYPT));

$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID " . $user->getId() . "\n";
