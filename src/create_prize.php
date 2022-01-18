<?php
$entityManager = require_once "./bootstrap/bootstrap_doctrine.php";

$newPrizeTitle = $argv[1];

$prize = new App\Models\Prize();
$prize->setTitle($newPrizeTitle);

$entityManager->persist($prize);
$entityManager->flush();

echo "Created Prize with ID " . $prize->getId() . "\n";
