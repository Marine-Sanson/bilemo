<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Service\UserService;
use OpenApi\Attributes as OA;
use App\Service\CustomerService;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Contracts\Cache\ItemInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly UserService $userService,
        private readonly SerializerInterface $serializer,
    )
    { }

    /**
     * Cette methode permet d'aller chercher tous les utilisateurs liès à un client à partir son id
     */
    #[Route('/api/users/{id}/customers', name: 'users_customers', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne tous les utilisateurs liès à un client',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Customer::class))
        )
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'La page que l\'on veut récupérer',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Le nombre d\'éléments que l\'on veut récupérer',
        schema: new OA\Schema(type: 'int')
    )]
    #[OA\Tag(name: 'Customer')]
    public function getAllCustomersOfAUser(int $id, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $idCache = "getAllCustomers-" . $page . "-" . $limit;

        $user = $this->userService->findOneById($id);
        $customersList = $cachePool->get($idCache, function (ItemInterface $item) use ($user, $page, $limit) {
            $item->tag("customerCache");

            return $this->customerService->findByUserWithPagination($user, $page, $limit);
        });
        $context = SerializationContext::create()->setGroups(['getAllCustomersOfAUser']);

        $jsonCustomersList = $this->serializer->serialize($customersList, 'json', $context);
        // return $this->json($jsonCustomersList, 200);
        return new JsonResponse($jsonCustomersList, Response::HTTP_OK, [], true);

    }

}
