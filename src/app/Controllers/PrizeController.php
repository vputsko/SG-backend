<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Repositories\PrizeRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PrizeController extends Controller
{

    protected PrizeRepositoryInterface $prizeRepository;

    public function __construct(PrizeRepositoryInterface $prizeRepository)
    {
        $this->prizeRepository = $prizeRepository;
    }
    
    public function showRandomPrize(): ResponseInterface
    {
        $prize = $this->prizeRepository->getRandomPrize();

        return $this->createResponse($prize);
    }
    
}