<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\PhoneService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
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

class PhoneController extends AbstractController
{

    public function __construct(
        private readonly PhoneService $phoneService,
        private readonly SerializerInterface $serializer,
    )
    { }

    /**
     * Cette methode permet de consulter l'ensemble des téléphones
     */
    #[Route('/api/phones', name: 'phones', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne l\'ensemble des téléphones',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Phone::class))
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
    #[OA\Tag(name: 'Phone')]

    public function getAllPhones(Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $context = SerializationContext::create()->setGroups(['getPhones']);

        $idCache = "getAllPhones-" . $page . "-" . $limit;
        $phonesList = $cachePool->get($idCache, function (ItemInterface $item) use ($page, $limit) {
            $item->tag("phoneCache");
            return $this->phoneService->findAllWithPagination($page, $limit);
        });
        $jsonPhonesList = $this->serializer->serialize($phonesList, 'json', $context);
        // return $this->json($jsonPhonesList, 200);
        return new JsonResponse($jsonPhonesList, Response::HTTP_OK, [], true);
    }

    /**
     * Cette methode permet d'aller chercher le détail d'un téléphone à partir son id
     */

    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne le détail d\'un téléphooone',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Phone::class))
        )
    )]
    #[OA\Tag(name: 'Phone')]
    public function getDetailPhones(Phone $phone): JsonResponse
    {
        $jsonPhone = $this->serializer->serialize($phone, 'json');
        // return $this->json($jsonPhone, 200, ['accept' => 'json']);
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);

    }

}
