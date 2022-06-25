<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Produit;;

class ProduitDataPersister implements DataPersisterInterface
{
  
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($data)
    {
        $this->entityManager->persist($data);       
        $this->entityManager->flush();       
    }

    public function supports($data): bool
    {
        return $data instanceof Produit;
    }

    public function remove($data)
    {
        $data->setIsEtat(false);
        $this->entityManager->flush();
    }
}