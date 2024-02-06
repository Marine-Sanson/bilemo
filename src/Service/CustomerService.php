<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;

Class CustomerService
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CustomerRepository $customerRepository,
    )
    {}
    public function findCustomer(int $userId)
    {

        return $this->userRepository->find($userId);

    }

    public function saveCustomer(Customer $customer): ?Customer
    {

        return $this->customerRepository->saveCustomer($customer);

    }

    public function deleteCustomer(Customer $customer):void
    {

        $this->customerRepository->deleteCustomer($customer);

    }

    public function findByUserWithPagination(User $user, int $page, int $limit): array
    {

        return $this->customerRepository->findByUserWithPagination($user, $page, $limit);

    }
    
}
