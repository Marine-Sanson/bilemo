<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Hateoas\Configuration\Annotation as Hateoas;

class PhoneController extends AbstractController
{

    public function __construct(
        private readonly PhoneRepository $phoneRepository,
        private readonly SerializerInterface $serializer,
    )
    { }

    #[Route('/api/phones', name: 'phones', methods: ['GET'])]
    public function getAllPhones(Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $context = SerializationContext::create()->setGroups(['getPhones']);

        $idCache = "getAllPhones-" . $page . "-" . $limit;
        $phonesList = $cachePool->get($idCache, function (ItemInterface $item) use ($page, $limit) {
            $item->tag("phoneCache");
            return $this->phoneRepository->findAllWithPagination($page, $limit);
        });
        $jsonPhonesList = $this->serializer->serialize($phonesList, 'json', $context);
        // return $this->json($jsonPhonesList, 200);
        return new JsonResponse($jsonPhonesList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailPhones(Phone $phone): JsonResponse
    {
        $jsonPhone = $this->serializer->serialize($phone, 'json');
        // return $this->json($jsonPhone, 200, ['accept' => 'json']);
        return new JsonResponse($jsonPhone, Response::HTTP_OK, ['accept' => 'json'], true);

    }

}
