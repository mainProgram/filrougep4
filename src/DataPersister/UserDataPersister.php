<?php

namespace App\DataPersister;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Gestionnaire;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserDataPersister implements DataPersisterInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;
    private MailerService $mailer;
    private ?TokenInterface $token;
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, MailerService $mailer)
    {
        $this->passwordHasher= $passwordHasher;
        $this->entityManager = $entityManager;
        $this->token = $tokenStorage->getToken();
        $this->mailer = $mailer;
    }

    
    public function supports($data): bool
    {
        return $data instanceof User ;
        // return $data instanceof User or $data instanceof Gestionnaire or  $data instanceof Client ;
    }


    /**
    * @param User|Gestionnaire|Client $data
    */
    public function persist($data)
    {
        if(!$data->getId())
        {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
            $data->setPassword($hashedPassword);
            // $role = $data->getDiscr() == "Gestionnaire" ? ["ROLE_GESTIONNAIRE"] : ["ROLE_CLIENT"];
            // $data->setRoles($role);
            $this->mailer->sendEmail($data);
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $data->setIsEtat(false);
        $this->entityManager->flush();
    }
}