<?php

declare(strict_types = 1);

namespace App\Repositories\Doctrine;

use App\Models\Prize;
use App\Repositories\PrizeRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
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
        $prize = $this->find($id);

        if (! $prize) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(
                $this->getEntityName(),
                ['id' => $id],
            );
        }

        return $prize;
    }

    /**
     * Finds prize by a set of criteria.
     *
     * @return Prize|object The prize
     * @throws EntityNotFoundException
     */
    public function getPrizeBy(array $criteria): Prize
    {
        $prize = $this->findOneBy($criteria);

        if (! $prize) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($this->getEntityName(), $criteria);
        }

        return $prize;
    }

    /**
     * Get random prize with random amount from the repository.
     *
     * @return array
     * @throws EntityNotFoundException
     */
    public function getRandomPrize(): array
    {
        $criteria = Criteria::create()->where(Criteria::expr()->gt('maxAmount', 0));
        $prizes = $this->matching($criteria)->toArray();

        if (! count($prizes)) {
            throw EntityNotFoundException::noIdentifierFound($this->getEntityName());
        }

        shuffle($prizes);

        /** @var Prize $rndPrize */
        $rndPrize = array_shift($prizes);

        return [
            'id' => $rndPrize->getId(),
            'title' => $rndPrize->getTitle(),
            'amount' => rand(1, $rndPrize->getMaxAmount()),
        ];
    }

}
