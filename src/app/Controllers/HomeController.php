<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Support\JsonResponse;

class HomeController
{

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show(): JsonResponse
    {
        $user = $this->userRepository->getUser(1);

        return response($user);
    }

}
