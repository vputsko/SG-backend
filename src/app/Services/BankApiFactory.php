<?php

declare(strict_types=1);


namespace App\Services;

use Symfony\Component\Console\Exception\RuntimeException;

class BankApiFactory
{

    private array $apis;

    public function __construct(array $apis)
    {
        $this->apis = $apis;
    }

    public function getBankApi(string $name): BankApiInterface
    {
        if ( array_key_exists($name, $this->apis) AND $this->apis[$name] instanceof BankApiInterface) {
            return $this->apis[$name];
        } else {
            throw new RuntimeException("There is wrong bank api class.");
        }
    }

}