<?php

declare(strict_types = 1);

/**
 * The bootstrap file creates and returns the container.
 */

require __DIR__ . '/../vendor/autoload.php';

return (new DI\ContainerBuilder())->addDefinitions(__DIR__ . '/../config/di.php')->build();
