<?php

declare(strict_types = 1);

/**
 * Parameters for Tuupola\Middleware\JwtAuthentication
 */

return [
    "secret" => getenv('JWT_SECRET'),
    "ignore" => ["/login", "/rnd_prize"],
];