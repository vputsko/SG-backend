<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\User;

interface UserRepository
{

    /**
     * Finds all users in the repository.
     *
     * @return array<User> The objects.
     */
    public function getUsers(): array;

    /**
     * Get user by id from the repository.
     *
     * @param mixed $id
     * @return User The user
     */
    public function getUser($id): User;

    /**
     * Finds user by a set of criteria.
     *
     * @return User The user.
     */
    public function getUsersBy(array $criteria): User;

}

