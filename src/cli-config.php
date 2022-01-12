<?php

declare(strict_types = 1);

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$entityManager = require "./bootstrap/bootstrap_doctrine.php";

return ConsoleRunner::createHelperSet($entityManager);
