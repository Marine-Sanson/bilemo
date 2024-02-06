<?php

namespace App\Service;

use App\Entity\Phone;
use App\Repository\PhoneRepository;

Class PhoneService
{

    public function __construct(
        private readonly PhoneRepository $phoneRepository,
    )
    {}

    public function findAllWithPagination(int $page, int $limit): array
    {

        return $this->phoneRepository->findAllWithPagination($page, $limit);

    }

}
