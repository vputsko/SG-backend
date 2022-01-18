<?php

declare(strict_types = 1);

namespace App;

use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use function function_exists;

if (! \function_exists('response')) {
    /**
     * Return a new response
     */
    function response(string $data, int $status = 200): ResponseInterface
    {
        return new TextResponse($data, $status, ['Content-Type' => 'application/json']);
    }
}
