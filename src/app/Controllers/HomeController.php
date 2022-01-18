<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Repositories\UserRepositoryInterface;
use App\Support\JsonResponse;

class HomeController
{

    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show(): JsonResponse
    {
        $user = $this->userRepository->getUser(1);

        return response($user);
    }

}
