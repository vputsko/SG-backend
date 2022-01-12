<?php

declare(strict_types = 1);

use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use function DI\get;
use function DI\create;
use function DI\factory;
use Psr\Container\ContainerInterface;
//use App\Support\JsonResponse;

if (! function_exists('response')) {
    /**
     * Return a new response
     *
     * @param string $data
     * @param  int  $status
     * @param array $headers
     * @return ResponseInterface
     */
    function response($data, int $status = 200): ResponseInterface
    {
        return new TextResponse($data, 200, ['Content-Type' => 'application/json']);
    }
}
