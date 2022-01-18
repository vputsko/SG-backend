<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Support\Traits\Serialized;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use function App\response;

class Controller
{

    use Serialized;

    /**
     * @var SymfonyRequest
     */
    protected $request;

    public function getRequest(): SymfonyRequest
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $serverRequest): void
    {
        $this->request = (new HttpFoundationFactory())->createRequest($serverRequest);
    }

    /**
     * @param array|object $data
     */
    protected function createResponse($data): ResponseInterface
    {
        return response($this->toJson($data));
    }

}