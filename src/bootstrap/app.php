<?php

declare(strict_types = 1);

/**
 * The bootstrap file creates and returns the container.
 */

require __DIR__ . '/../vendor/autoload.php';

$bugsnag = Bugsnag\Client::make(getenv('BUGSNAG_TOKEN'));
Bugsnag\Handler::register($bugsnag);

return (new DI\ContainerBuilder())->addDefinitions(__DIR__ . '/../config/di.php')->build();
