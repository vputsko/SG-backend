<?php

declare(strict_types = 1);

/**
 * The bootstrap file creates and returns the container.
 */

require __DIR__ . '/../vendor/autoload.php';

$bugsnag = Bugsnag\Client::make(getenv('BUGSNAG_TOKEN'));
Bugsnag\Handler::register($bugsnag);

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/di.php');

//Development injection
if ('1' === getenv('LOCAL')) {
    $containerBuilder->addDefinitions(__DIR__ . '/../config/di.dev.php');
} else {
    //Caching for production
    $containerBuilder->enableCompilation('/var/tmp/cache');
    $containerBuilder->enableDefinitionCache();
}


return $containerBuilder->build();
