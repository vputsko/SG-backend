<?php

declare(strict_types = 1);

namespace App\Support\Traits;

use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

trait RequestFormatter
{

    /** @var SymfonyRequest */
    protected SymfonyRequest $request;

    protected function getRequest(ServerRequestInterface $serverRequest): void
    {
        $this->request = (new HttpFoundationFactory())->createRequest($serverRequest);
    }

}
