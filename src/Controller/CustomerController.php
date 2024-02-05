<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Hateoas\Configuration\Annotation as Hateoas;

class CustomerController extends AbstractController
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer,        
        private readonly EntityManagerInterface $em,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {}

    #[Route('/api/customers/{id}', name: 'detailCustomer', methods: ['GET'])]
    public function getDetailCustomer(Customer $customer): JsonResponse
    {

        $context = SerializationContext::create()->setGroups(['getDetailCustomer']);
        // $customer = $this->customerRepository->findOneById($id);
        $jsonCustomer = $this->serializer->serialize($customer, 'json', $context);
        // return $this->json($jsonPhonesList, 200);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
        
    }


    #[Route('/api/customers', name: 'newCustomer', methods: ['POST'])]
    public function newCustomer(Request $request): JsonResponse
    {

        $customer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');
        $content = $request->toArray();
        $userId = $content['userId'] ?? -1;
        $customer->setUser($this->userRepository->find($userId));

        $this->em->persist($customer);
        $this->em->flush();
        $context = SerializationContext::create()->setGroups(['getDetailCustomer']);
        $jsonCustomer = $this->serializer->serialize($customer, 'json', $context);
        $location = $this->urlGenerator->generate('detailCustomer', ['id' => $customer->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCustomer, Response::HTTP_CREATED, ["Location" => $location], true);

    }
    
    #[Route('/api/customers/{id}', name: 'deleteCustomer', methods: ['DELETE'])]
    public function deleteCustomer(Customer $customer): JsonResponse
    {

        $this->em->remove($customer);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


}
