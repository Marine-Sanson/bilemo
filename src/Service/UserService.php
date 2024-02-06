<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

Class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function findOneById(int $id): ?User
    {

        return $this->userRepository->findOneById($id);

    }


}
