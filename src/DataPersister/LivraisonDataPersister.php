<?php

namespace App\DataPersister;

use App\Entity\Livraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class LivraisonDataPersister implements DataPersisterInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Livraison;
    }

    
    public function persist($data)
    {
        $commandes = $data->getCommandes();

        foreach($commandes as $commande)
        {
            if(!$commande->getZone())
                return new JsonResponse( ["error" => "Commande sans zone de livraison!"], 400);
            elseif($commandes[0]->getZone() != $commande->getZone())
                return new JsonResponse( ["error" => "Pas de zones de livraison différentes!"], 400);
        }
        dd($data);

        $this->entityManager->persist($data);       
        $this->entityManager->flush();       
    }

  
    public function remove($data)
    {
    }
}