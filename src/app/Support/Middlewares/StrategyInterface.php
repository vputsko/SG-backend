<?php

declare(strict_types = 1);

namespace App\Support\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Source - Helper classes for Mezzio - https://github.com/mezzio/mezzio-helpers
 */
interface StrategyInterface
{
    /**
     * Match the content type to the strategy criteria.
     *
     * @return bool Whether or not the strategy matches.
     */
    public function match(string $contentType): bool;

    /**
     * Parse the body content and return a new request.
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface;
}