<?php

declare(strict_types = 1);

namespace App;

use Laminas\Diactoros\Response\TextResponse;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use function function_exists;

if (! function_exists('container')) {
    /**
     * Return the container
     * 
     * @return Container
     */
    function container(): Container
    {
        return require __DIR__ . "/../bootstrap/app.php";
    }
}

if (! function_exists('response')) {
    /**
     * Return a new response
     */
    function response(string $data, int $status = 200): ResponseInterface
    {
        return new TextResponse($data, $status, ['Content-Type' => 'application/json']);
    }
}
