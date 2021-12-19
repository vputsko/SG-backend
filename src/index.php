<?php
use Bramus\Router\Router;

require __DIR__.'/vendor/autoload.php';

$router = new Router();

$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
});

$router->setNamespace('\App\Controllers');
$router->get('/users', 'Error@showUsers');

$router->get('/', function () {
    echo '<h1>vpumuk/sg</h1><p>The SG tests.<p>';
});

$router->run();
