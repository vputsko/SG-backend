<?php

declare(strict_types = 1);

namespace App\Repositories\Doctrine;

use App\Models\User;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{

    /**
     * Finds all users in the repository.
     *
     * @return array<object> The users.
     */
    public function getUsers(): array
    {
        return $this->findAll();
    }

    /**
     * Get user by id from the repository.
     *
     * @param mixed $id
     * @return \App\Models\User|object The user
     * @throws EntityNotFoundException
     */
    public function getUser($id): User
    {
        $user = $this->find($id);

        if (! $user) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(
                $this->getEntityName(),
                ['id' => $id],
            );
        }

        return $user;
    }

    /**
     * Finds user by a set of criteria.
     *
     * @param array $criteria
     * @return User|object The user.
     * @throws EntityNotFoundException
     */
    public function getUsersBy(array $criteria): User
    {
        $user = $this->findOneBy($criteria);

        if (! $user) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($this->getEntityName(), $criteria);
        }

        return $user;
    }

}
