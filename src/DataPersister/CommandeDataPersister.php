<?php

namespace App\DataPersister;

use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Quartier;
use App\Entity\Zone;

class CommandeDataPersister implements DataPersisterInterface
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
        return $data instanceof Zone or $data instanceof Quartier;
    }

    public function remove($data)
    {
        $data->setIsEtat(false);
        $this->entityManager->flush();
    }
}