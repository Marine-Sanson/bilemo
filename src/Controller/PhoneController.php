<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\PhoneService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
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
     * 
     * @Route("api/phones", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des téléphones",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class, groups={"getPhones"}))
     *     )
     * )
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
     * @OA\Tag(name="Phone")
     * @return JsonResponse
     *
     */
    #[Route('/api/phones', name: 'phones', methods: ['GET'])]
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
     * 
     * @Route("api/phones/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Retourne le détail d'un téléphone",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Phone::class))
     *     )
     * )
     * @OA\Tag(name="Phone")
     *
     * @param Phone $phone Phone
     * 
     * @return JsonResponse
     */
    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailPhones(Phone $phone): JsonResponse
    {
        $jsonPhone = $this->serializer->serialize($phone, 'json');
        // return $this->json($jsonPhone, 200, ['accept' => 'json']);
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);

    }

}
