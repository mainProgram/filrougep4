<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Client;
use App\Entity\Gestionnaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $password = "passer";
        
        $gest = new Gestionnaire();
        $hashedPassword = $this->passwordHasher->hashPassword($gest, $password );
        $gest->setNom($faker->firstName)
            ->setPrenom($faker->lastName)
            ->setTelephone($faker->phoneNumber)
            ->setEmail("fazeynafaye@gmail.com")
            ->setPassword($hashedPassword)       
            ->setRoles(["ROLE_GESTIONNAIRE"]);        
        $manager->persist($gest);   

        $client = new Client();
        $hashedPassword = $this->passwordHasher->hashPassword($client, $password );
        $client->setNom($faker->firstName)
            ->setPrenom($faker->lastName)
            ->setTelephone($faker->phoneNumber)
            ->setEmail("awadiop425@gmail.com")
            ->setRoles(["ROLE_CLIENT"])      
            ->setPassword($hashedPassword);
        $manager->persist($client);    
    

        $manager->flush();
    }
}
