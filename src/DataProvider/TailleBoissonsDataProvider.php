<?php
#

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use App\Entity\TailleBoisson;
use App\Repository\TailleBoissonRepository;

class TailleBoissonsDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(TailleBoissonRepository $tailleBoissonRepository)
    {
        $this->tailleBoissonRepository = $tailleBoissonRepository;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    { 
        $qb = $this->tailleBoissonRepository->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', 1)->andWhere('tbrepo.quantiteStock > 0');
        $query = $qb->getQuery();
        $lesTaillesPMBoissonsDispo = $query->execute();

        $qb = $this->tailleBoissonRepository->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', 2)->andWhere('tbrepo.quantiteStock > 0');
        $query = $qb->getQuery();
        $lesTaillesGMBoissonsDispo = $query->execute();

        $retour["pm"] = $lesTaillesPMBoissonsDispo;
        $retour["gm"] = $lesTaillesGMBoissonsDispo;
        return  $retour;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == TailleBoisson::class;
    }
    
}