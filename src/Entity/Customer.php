<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "detailCustomer",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getAllCustomersOfAUser")
 * )
 * 
 *  * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "deleteCustomer",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getAllCustomersOfAUser", excludeIf = "expr(not is_granted('ROLE_ADMIN'))"),
 * )
 *
 */
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllCustomersOfAUser", "getDetailCustomer"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getAllCustomersOfAUser", "getDetailCustomer"])]
    #[Assert\NotBlank(message: "Votre nom est obligatoire")]
    #[Assert\Length(min: 1, max: 50, minMessage: "Votre nom doit faire au moins {{ limit }} caractères", maxMessage: "Votre nom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getAllCustomersOfAUser", "getDetailCustomer"])]
    #[Assert\NotBlank(message: "Votre prénom est obligatoire")]
    #[Assert\Length(min: 1, max: 50, minMessage: "Votre prénom doit faire au moins {{ limit }} caractères", maxMessage: "Votre prénom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $fistName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getDetailCustomer"])]
    private ?string $adress = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getDetailCustomer"])]
    private ?int $postCode = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["getDetailCustomer"])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllCustomersOfAUser", "getDetailCustomer"])]
    #[Assert\NotBlank(message: "Votre email est obligatoire")]
    #[Assert\Length(min: 5, max: 255, minMessage: "Votre email doit faire au moins {{ limit }} caractères", maxMessage: "Votre email ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getDetailCustomer"])]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFistName(): ?string
    {
        return $this->fistName;
    }

    public function setFistName(string $fistName): static
    {
        $this->fistName = $fistName;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPostCode(): ?int
    {
        return $this->postCode;
    }

    public function setPostCode(?int $postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}
