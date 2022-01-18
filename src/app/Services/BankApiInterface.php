<?php

namespace App\Services;

interface BankApiInterface
{
    /**
     * @param  array  $request
     * @return array
     */
    public function sendMoney(array $request): array;

}