<?php

namespace App\Controller;

use App\Service\UserService;
use OpenApi\Annotations as OA;
use App\Service\CustomerService;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Contracts\Cache\ItemInterface;
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
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne tous les utilisateurs liès à un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Customer::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * 
     * @OA\Tag(name="Customer")
     * @Security(name="Bearer")
     *
     * @param int $id idUser
     * @param TagAwareCacheInterface $cachePool
     * 
     * @return JsonResponse
     */
    #[Route('/api/users/{id}/customers', name: 'users_customers', methods: ['GET'])]
    public function getAllCustomersOfAUser(int $id, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $idCache = "getAllPhones-" . $page . "-" . $limit;

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
