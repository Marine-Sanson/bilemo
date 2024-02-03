<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer,
    )
    { }

    #[Route('/api/users/{id}/customers', name: 'users_customers', methods: ['GET'])]
    public function getAllCustomersOfAUser(int $id, Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $user = $this->userRepository->findOneById($id);
        $customersList = $this->customerRepository->findByUserWithPagination($user, $page, $limit);
        $jsonCustomersList = $this->serializer->serialize($customersList, 'json', ['groups' => 'getAllCustomersOfAUser']);
        // return $this->json($jsonPhonesList, 200);
        return new JsonResponse($jsonCustomersList, Response::HTTP_OK, [], true);
    }

}
