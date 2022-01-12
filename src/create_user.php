<?php
$entityManager = require_once "./bootstrap/bootstrap_doctrine.php";

$newUserName = $argv[1];

$user = new App\Models\User();
$user->setName($newUserName);
$user->setEmail('vpu.muk@gmail.com');
$user->setPassword(password_hash('password', PASSWORD_BCRYPT));

$entityManager->persist($user);
$entityManager->flush();

echo "Created Product with ID " . $user->getId() . "\n";
