<?php 

declare(strict_types = 1);

use App\Kernel\Application;

require __DIR__ . "/../bootstrap/app.php";
require_once __DIR__ . "/../app/helpers.php";

$app = new Application( );
$app->run();
