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

        //-------------------------------------------------------------------------------------------------ERREURS
        foreach($commandes as $commande)
        {
            if(!$commande->getZone())
                return new JsonResponse( ["error" => "Commande sans zone de livraison!"], 400);
            elseif($commandes[0]->getZone() != $commande->getZone())
                return new JsonResponse( ["error" => "Pas de zones de livraison différentes!"], 400);
            elseif($commande->getEtat() != "termine")
                return new JsonResponse( ["error" => "Pas de commande non terminées !"], 400);
        }

        //-------------------------------------------------------------------------------------------------SET ETAT EN COURS DE LIVRAISON
        if($data->getLivreur() != null)
        {
            foreach($commandes as $commande)
                $commande->setEtat("livraison"); //Changement des etat des commandes

            $data->getLivreur()->setIsDisponible(0); //changement de la disponibilité du livreur
        }

        $this->entityManager->persist($data);       
        $this->entityManager->flush();       
    }

  
    public function remove($data)
    {
    }
}