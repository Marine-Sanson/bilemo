<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{

    public function __construct(
        private readonly PhoneRepository $phoneRepository,
        private readonly SerializerInterface $serializer,
    )
    { }

    #[Route('/api/phones', name: 'phones', methods: ['GET'])]
    public function getAllPhones(): JsonResponse
    {
        $phonesList = $this->phoneRepository->findAll();
        $jsonPhonesList = $this->serializer->serialize($phonesList, 'json');
        // return $this->json($jsonPhonesList, 200);
        return new JsonResponse($jsonPhonesList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/phones/{id}', name: 'detailPhone', methods: ['GET'])]
    public function getDetailPhones(Phone $phone): JsonResponse
    {
        $jsonPhone = $this->serializer->serialize($phone, 'json');
        return $this->json($jsonPhone, 200, ['accept' => 'json']);
    }

}
