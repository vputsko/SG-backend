<?php

declare(strict_types = 1);

namespace App\Messages;

class SendMoneyMessage
{
    private int $paymentId;

    public function __construct(int $paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getPaymentId(): int
    {
        return $this->paymentId;
    }
}