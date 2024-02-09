<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use App\Service\CustomerService;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService,
        private readonly UserService $userService,
        private readonly SerializerInterface $serializer,        
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {}

    /**
     * Cette methode permet d'aller chercher le détail d'un utilisateur à partir son id
     */
    #[Route('/api/customers/{id}', name: 'detailCustomer', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne le détail d\'un utilisateur',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Customer::class))
        )
    )]
    #[OA\Tag(name: 'Customer')]

    public function getDetailCustomer(Customer $customer): JsonResponse
    {

        $context = SerializationContext::create()->setGroups(['getDetailCustomer']);
        $jsonCustomer = $this->serializer->serialize($customer, 'json', $context);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
        
    }

    /**
     * Cette methode permet de créer un nouvel utilisateur et de le lier à un client
     */
    #[Route('/api/customers', name: 'newCustomer', methods: ['POST'])]
    #[OA\RequestBody(content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Customer::class, groups: ['newCustomer'])),)
    )]
    #[OA\Response(
        response: 200,
        description: 'Création un nouvel utilisateur',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Customer::class, groups: ['getDetailCustomer'])),
        )
    )]
    #[OA\Tag(name: 'Customer')]

    public function newCustomer(Request $request): JsonResponse
    {

        $newCustomer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');
        $content = $request->toArray();
        $userId = $content['userId'] ?? -1;
        $newCustomer->setUser($this->customerService->findCustomer($userId));

        $customer = $this->customerService->saveCustomer($newCustomer);

        $context = SerializationContext::create()->setGroups(['getDetailCustomer']);
        $jsonCustomer = $this->serializer->serialize($customer, 'json', $context);
        $location = $this->urlGenerator->generate('detailCustomer', ['id' => $customer->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCustomer, Response::HTTP_CREATED, ["Location" => $location], true);

    }

    /**
     * Cette methode permet de supprimer un utilisateur à partir son id
     */
    #[Route('/api/customers/{id}', name: 'deleteCustomer', methods: ['DELETE'])]
    #[OA\Response(
        response: 204,
        description: 'Supprime un utilisateur',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Customer::class))
        )
    )]
    #[OA\Tag(name: 'Customer')]

    public function deleteCustomer(Customer $customer): JsonResponse
    {

        $this->customerService->deleteCustomer($customer);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);

    }

}
