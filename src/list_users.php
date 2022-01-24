<?php

declare(strict_types = 1);

$entityManager = require_once "./bootstrap/bootstrap_doctrine.php";

$userRepository = $entityManager->getRepository('App\Models\User');
$users = $userRepository->findAll();

var_dump($users);
