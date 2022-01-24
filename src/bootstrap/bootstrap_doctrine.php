<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require "app.php";

$isDevMode = getenv('LOCAL') == 1;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/../config/xml"), $isDevMode, $proxyDir, $cache);


$db_params = [
    'driver' => getenv('DB_DRIVER'),
    'host' => getenv('DB_HOST'),
    'dbname' => getenv('DB_DATABASE'),
    'user' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'path' => '/var/tmp/db.sqlite',
];

return EntityManager::create($db_params, $config);
