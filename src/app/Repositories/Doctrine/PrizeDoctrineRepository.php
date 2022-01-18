<?php

declare(strict_types = 1);

namespace App\Repositories\Doctrine;

use App\Models\Prize;
use App\Repositories\PrizeRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class PrizeDoctrineRepository extends EntityRepository implements PrizeRepositoryInterface
{

    /**
     * Finds all prizes in the repository.
     *
     * @return array<Prize|object> The objects.
     */
    public function getPrizes(): array
    {
        return $this->findAll();
    }

    /**
     * Get prize by id from the repository.
     *
     * @param mixed $id
     * @return Prize|object The prize
     * @throws EntityNotFoundException
     */
    public function getPrize($id): Prize
    {
        $prise = $this->find($id);

        if (! $prise) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(
                $this->getEntityName(),
                ['id' => $id],
            );
        }

        return $prise;
    }

    /**
     * Finds prize by a set of criteria.
     *
     * @return Prize|object The prize
     * @throws EntityNotFoundException
     */
    public function getPrizeBy(array $criteria): Prize
    {
        $prise = $this->findOneBy($criteria);

        if (! $prise) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($this->getEntityName(), $criteria);
        }

        return $prise;
    }

}
