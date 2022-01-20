<?php

declare(strict_types = 1);

use Doctrine\ORM\Tools\Setup;
use Monolog\Handler\LogglyHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;
use function DI\create;
use function DI\env;
use function DI\get;

return [
    'doctrine.config' => Setup::createXMLMetadataConfiguration(array(__DIR__."/xml"), true, null, null),
    LoggerInterface::class => create(Logger::class)->constructor('sg', [get(LogglyHandler::class)] , [ get(PsrLogMessageProcessor::class)]), //new NullLogger()
];