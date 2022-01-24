<?php

declare(strict_types = 1);

use App\Controllers\LoginController;
use App\Controllers\PrizeController;
use App\Controllers\UserController;

return [
    ['POST', '/login[/]', LoginController::class],
    ['GET', '/users[/]', [UserController::class, 'showUsers']], //(for demo purposes only)
    ['GET', '/user/{id:\d+}', [UserController::class, 'showUser']], //(for demo purposes only)
    ['GET', '/iam[/]', [UserController::class, 'showCurrentUser']],
    ['GET', '/rnd_prize[/]', [PrizeController::class, 'showRandomPrize'] ],
];