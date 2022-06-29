<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Boisson;
use App\Entity\Burger;
use App\Entity\Frite;
use App\Entity\Menu;
use App\Entity\Produit;


class ProduitDataPersister implements DataPersisterInterface
{
  
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($data)
    {
        if($data instanceof Boisson)
            $data->setPrix((0));
        elseif ($data instanceof Menu)
        {
            $prix = 0;

            foreach($data->getBurgers() as $burger)
                $prix+= $burger->getPrix();

            foreach($data->getFrites() as $frite)
                $prix+= $frite->getPrix();

            foreach($data->getTailleBoissons() as $boisson)
                $prix+= $boisson->getPrix();

            $prix -= $prix * 0.05;
            $data->setPrix($prix);
        }
        $this->entityManager->persist($data);       
        $this->entityManager->flush();       
    }

    public function supports($data): bool
    {
        return $data instanceof Produit or $data instanceof Burger or $data instanceof Frite or $data instanceof Boisson or $data instanceof Menu ;
    }

    public function remove($data)
    {
        $data->setIsEtat(false);
        $this->entityManager->flush();
    }
}