<?php

namespace App\Services;

interface BankApiInterface
{
    /**
     * @param  array  $request
     */
    public function sendMoney(array $request);

}