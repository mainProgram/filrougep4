<?php

namespace App\DataPersister;

use App\Entity\Menu;
use App\Entity\Zone;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Quartier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Gestionnaire;
use App\Repository\TailleBoissonRepository;
use App\Services\CommandeService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeDataPersister implements DataPersisterInterface
{
  
    public function __construct(private EntityManagerInterface $entityManager, private EntityManagerInterface $entityManager2, private TokenStorageInterface $token, private TailleBoissonRepository $tailleBoissonRepository, private CommandeService $commandeService){}

    public function persist($data)
    {
        ($this->commandeService->quantiteChoisieVsQuantiteMenu($data));
        // $user = $this->token->getToken()->getUser();

        // if($user instanceof Client)
        //     $data->setClient($user);
        // elseif($user instanceof Gestionnaire)
        //     $data->setGestionnaire($user);

        // $lignesDeCommandes = $data->getCommandeProduits();
        

        // //----------------------------------------------------------------------------YA DES BOISSONS DANS LES MENUS ?
        // foreach($lignesDeCommandes as $ldc)
        // if($ldc->getProduit() instanceof Menu)
        // {
        //     //----------------------------------------------------------------------------Quantité choisie == quantité du menu ?
        //     $taillesDuMenu = $ldc->getProduit()->getMenuTailles();
        //     $quantiteBoissons = 0 ;  
        //     foreach($taillesDuMenu as $t)
        //         $quantiteBoissons += $t->getQuantite();

        //     $boissonsChoisies = $ldc->getTailleBoissons();

        //     if(count($boissonsChoisies) != $quantiteBoissons)
        //         return new JsonResponse( ["error" => "Le menu ".$ldc->getProduit()->getNom()." a ".count($taillesDuMenu)." boisson (s)!"], 400);


        //     foreach($taillesDuMenu as $taille)
        //     {
               
        //         $idTaille = $taille->getTaille()->getId();
        //         $quantite = $taille->getQuantite();
        //         $combienDeModeles = [];

        //         //----------------------------------------------------------------------------Nombre de PM dans le menu == nombre de PM choisi
        //         foreach($boissonsChoisies as $bc)
        //             if($bc->getTaille()->getId() == $idTaille)
        //                 $combienDeModeles[] = $bc;
                
        //         if($quantite != count($combienDeModeles))
        //             return new JsonResponse( ["error" => "Le menu ".$ldc->getProduit()->getNom()." a ".$quantite." boisson (s) ".$taille->getTaille()->getNom()."!"], 400);

        //         //----------------------------------------------------------------------------Lim choisir disponible ne ?
        //         $qb = $this->tailleBoissonRepository->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', $idTaille)->andWhere('tbrepo.quantiteStock > 0');
        //         $query = $qb->getQuery();
        //         $lesTaillesBoissonsDispo = $query->execute();

        //         foreach($combienDeModeles as $bc)
        //             if(!in_array($bc, $lesTaillesBoissonsDispo))
        //                 return new JsonResponse( ["error" => "La boisson ".$bc->getBoisson()->getNom()." en ".$bc->getTaille()->getNom()." n'est pas disponible !"], 400);               

        //         //----------------------------------------------------------------------------Lim choisir stock bi amnafi ?
        //         // foreach($boissonsChoisies as $bc)
        //         //     if(!in_array($bc, $lesTaillesBoissonsDispo))
                      
        //     }
        // }

        //----------------------------------------------------------------------------YA TIL DES COMPLEMENTS BOISSONS DANS LES COMMANDES ?
            
        //----------------------------------------------------------------------------Prix total commande 
        $this->commandeService->calculPrix($data);
       
        //----------------------------------------------------------------------------

        $this->entityManager->persist($data);       
        $this->entityManager->flush();   
        $data->setNumero("#".$data->getId()); 
        $this->entityManager2->flush();    
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

