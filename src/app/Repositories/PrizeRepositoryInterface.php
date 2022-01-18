<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Prize;

interface PrizeRepositoryInterface
{

    /**
     * Finds all prizes in the repository.
     *
     * @return array<Prize|object> The objects.
     */
    public function getPrizes(): array;

    /**
     * Get prize by id from the repository.
     *
     * @param mixed $id
     * @return Prize|object The prize
     */
    public function getPrize($id): Prize;

    /**
     * Finds prize by a set of criteria.
     *
     * @return Prize|object The prize
     */
    public function getPrizeBy(array $criteria): Prize;

}

