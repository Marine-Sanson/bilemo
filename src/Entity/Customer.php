<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllCustomersOfAUser", "getCustomerDetail"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getAllCustomersOfAUser", "getCustomerDetail"])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getAllCustomersOfAUser", "getCustomerDetail"])]
    private ?string $fistName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getCustomerDetail"])]
    private ?string $adress = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getCustomerDetail"])]
    private ?int $postCode = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(["getCustomerDetail"])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllCustomersOfAUser", "getCustomerDetail"])]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getCustomerDetail"])]
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
