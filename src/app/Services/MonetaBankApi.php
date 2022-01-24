<?php

declare(strict_types = 1);

namespace App\Services;

use Psr\Log\LoggerInterface;

class MonetaBankApi implements BankApiInterface
{

    private LoggerInterface $logger;


    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $request
     */
    public function sendMoney(array $request)
    {
        $this->logger->debug('MonetaBankApi - sendMoney', $request);
        // TODO: Implement sendMoney() method.
    }
    
}