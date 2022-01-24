<?php

declare(strict_types = 1);

namespace App;

use App\Kernel\Application;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use function function_exists;

if (! function_exists('container')) {
    /**
     * Return the container
     * 
     * @return ContainerInterface
     */
    function container(): ContainerInterface
    {
        return Application::buildContainer();
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
