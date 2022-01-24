<?php

declare(strict_types=1);

namespace App\Repositories\Doctrine;

use App\Repositories\PaymentsRepositoryInterface;

class PaymentsDoctrineRepository implements PaymentsRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getPayment(int $id): array
    {
        return [
            'id' => $id,
            'user' => [],
            'amount' => [],
            'bank_api' => 'App\Services\MonetaBankApi',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAwaitingPayments(int $limit): array
    {
        return range(1, $limit);
    }
    
}