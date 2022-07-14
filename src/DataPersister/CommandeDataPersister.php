<?php

namespace App\DataPersister;

use App\Entity\Menu;
use App\Entity\Zone;
use App\Entity\Burger;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Quartier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Repository\TailleBoissonRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeDataPersister implements DataPersisterInterface
{
  
    public function __construct(private EntityManagerInterface $entityManager, private TokenStorageInterface $token, private TailleBoissonRepository $tailleBoissonRepository)
    {
    }

    public function persist($data)
    {
        
        $user = $this->token->getToken()->getUser();

        if($data instanceof Commande && $user instanceof Client)
        {
            //----------------------------------------------------------------------------Client 
            $data->setClient($user);

            //------------------------------y'a til un burger ou un menu d'abord ?
            $lignesDeCommandes = $data->getCommandeProduits();
            $found = false;
            foreach($lignesDeCommandes as $ldc)
                if($ldc->getProduit() instanceof Menu or $ldc->getProduit() instanceof Burger)
                    $found = true;  
            
            if(!$found)
                return new JsonResponse( ["error" => "Commande sans Burger ou Menu !"], 400);

            //----------------------------------------------------------------------------YA DES BOISSONS DANS LES MENUS ?
            foreach($lignesDeCommandes as $ldc)
            if($ldc->getProduit() instanceof Menu)
            {
                $tailles = $ldc->getProduit()->getMenuTailles();  
                foreach($tailles as $taille)
                {
                    $idTaille = $taille->getTaille()->getId();

                    $lesTailles = ($this->tailleBoissonRepository->findBy(["taille" => $idTaille]));

                    $boissonsDispo =  [];
                    foreach($lesTailles as $lt)
                        if($lt->getQuantiteStock() > 0)
                            $boissonsDispo[] = $lt->getBoisson()->getNom();

                     dump($boissonsDispo);
                }
            }


            //----------------------------------------------------------------------------Prix total commande 
            $prix = 0;
            foreach($lignesDeCommandes as $ldc)
                $prix += $ldc->getQuantite() * $ldc->getProduit()->getPrix() ; 

            $zone = $data->getZone();
            if($zone)
                $prix += $zone->getPrix();
                
            $data->setPrix($prix);

            //----------------------------------------------------------------------------
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