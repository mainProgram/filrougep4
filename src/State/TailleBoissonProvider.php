<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\TailleBoissonRepository;

class TailleBoissonProvider implements ProviderInterface
{
    public function __construct(TailleBoissonRepository $tailleBoissonRepository)
    {
        $this->tailleBoissonRepository = $tailleBoissonRepository;
    }
    
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
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
}
