<?php

declare(strict_types = 1);

return [
    'driver' => getenv('DB_DRIVER'),
    'host' => getenv('DB_HOST'),
    'dbname' => getenv('DB_DATABASE'),
    'user' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'path' => '/var/tmp/db.sqlite',
];
