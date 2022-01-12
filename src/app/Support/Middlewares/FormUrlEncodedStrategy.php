<?php

declare(strict_types=1);


namespace App\Support\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Source - Helper classes for Mezzio - https://github.com/mezzio/mezzio-helpers
 */
class FormUrlEncodedStrategy implements StrategyInterface
{

    public function match(string $contentType): bool
    {
        return 1 === preg_match('#^application/x-www-form-urlencoded($|[ ;])#', $contentType);
    }

    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $parsedBody = $request->getParsedBody();

        if (! empty($parsedBody)) {
            return $request;
        }

        $rawBody = (string) $request->getBody();

        if (empty($rawBody)) {
            return $request;
        }

        parse_str($rawBody, $parsedBody);

        return $request->withParsedBody($parsedBody);
    }
}