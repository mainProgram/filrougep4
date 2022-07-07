<?php

namespace App\DataPersister;

use App\Entity\Zone;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Quartier;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeDataPersister implements DataPersisterInterface
{
  
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager, private TokenStorageInterface $token)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($data)
    {
        $user = $this->token->getToken()->getUser();

        if($data instanceof Commande && $user instanceof Client)
        {
            //----------------------------------------------------------------------------Client 
            $data->setClient($user);

            //----------------------------------------------------------------------------Prix total commande
            $lignesDeCommandes = $data->getCommandeProduits();
            $prix = 0;
            foreach($lignesDeCommandes as $ldc)
                $prix += $ldc->getQuantite() * $ldc->getProduit()->getPrix() ; 

            $zone = $data->getZone();
            if($zone)
                $prix += $zone->getPrix();
                
            $data->setPrix($prix);

            //----------------------------------------------------------------------------
            dd($data);
        }
            
        $this->entityManager->persist($data);       
        $this->entityManager->flush();       
    }

    public function supports($data): bool
    {
        return $data instanceof Zone or $data instanceof Quartier or $data instanceof Commande;
    }

    public function remove($data)
    {
        $data->setIsEtat(false);
        $this->entityManager->flush();
    }
}