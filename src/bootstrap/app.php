<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$bugsnag = Bugsnag\Client::make(getenv('BUGSNAG_TOKEN'));
Bugsnag\Handler::register($bugsnag);
