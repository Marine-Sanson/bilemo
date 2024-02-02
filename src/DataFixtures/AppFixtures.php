<?php

namespace App\DataFixtures;

use FR\Address;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Phone;
use Faker\Provider\fr;
use Random\Randomizer;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher){
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $storage = [32, 64, 128, 256, 512, 1000];
        $screenSize = [5.5, 5.6, 5.7, 5.8, 5.9, 6, 6.1, 6.2, 6.3, 6.4, 6.5, 6.6, 6.7, 6.8, 6.9];
        $color = ["Noir", "Bleu", "Blanc", "Vert", "Violet", "Gris", "Beige", "Rouge"];
        $brand =  $faker->words(5);

        for ($i = 0; $i < 15; $i++) {
            $description = $faker->sentences(rand(1, 5), true);
            if (strlen($description) >= 255 ){
                $description = substr($description, 0, 255);
            }

            $phone = (new Phone())
                ->setModel($faker->word() . " " . rand(1, 20))
                ->setBrand($brand[rand(0, 4)])
                ->setDescription($description)
                ->setStorage(array_rand($storage))
                ->setScreenSize(array_rand($screenSize))
                ->setColor($color[array_rand($color)])
                ->setLength(rand(135, 170))
                ->setWidth(rand(65, 80))
                ->setHeight(rand(6, 9))
                ->setWeight(rand(148, 205))
                ->setRepairabilityIndex(rand(0, 10) . "." . rand(0, 99));

            $manager->persist($phone);
            $manager->flush();
        }

        $listUsers = [];
        for ($i = 0; $i <5; $i++) {
            $role = ["ROLE_USER"];
            $x = mt_rand(0, 9);

            if ($x === 7) {
                $role = ["ROLE_ADMIN"];
            }

            $user = (new User())
                ->setUsername($faker->word())
                ->setRoles($role);
            
            $password = $this->userPasswordHasher->hashPassword($user, 'mdpass');
            $user->setPassword($password);
            
            $manager->persist($user);
            $manager->flush();

            $listUsers[] = $user;
        }

        for ($i = 0; $i <50; $i++) {
            $customer = (new Customer())
                ->setUser($listUsers[array_rand($listUsers)])
                ->setName($faker->lastName())
                ->setFistName($faker->firstName())
                ->setAdress($faker->streetAddress())
                ->setPostCode($faker->postcode())
                ->setCity($faker->city())
                ->setEmail($faker->email());

        $manager->persist($customer);
        $manager->flush();

        }

    }
}
