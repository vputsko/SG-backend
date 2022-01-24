<?php

declare(strict_types = 1);

namespace App\Repositories;

interface PaymentsRepositoryInterface
{
    
    /**
     * Get payment by id
     * 
     * @param  int  $id
     * @return array
     */
    public function getPayment(int $id): array;
    
    /**
     * Get awaiting payment ids for clients
     * 
     * @param int $limit
     * @return array
     */
    public function getAwaitingPayments(int $limit): array;

}