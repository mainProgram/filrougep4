<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use App\Repository\FriteRepository;
use ApiPlatform\State\ProviderInterface;
use App\Repository\TailleBoissonRepository;

class ComplementProvider implements ProviderInterface
{

    public function __construct(FriteRepository $friterepo, TailleBoissonRepository $tbrepo)
    {
        $this->tbrepo = $tbrepo;
        $this->friterepo = $friterepo;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $qb = $this->tbrepo->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', 1)->andWhere('tbrepo.quantiteStock > 0');
        $query = $qb->getQuery();
        $lesTaillesPMBoissonsDispo = $query->execute();

        $qb = $this->tbrepo->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', 2)->andWhere('tbrepo.quantiteStock > 0');
        $query = $qb->getQuery();
        $lesTaillesGMBoissonsDispo = $query->execute();

        $tab["pm"] = $lesTaillesPMBoissonsDispo;
        $tab["gm"] = $lesTaillesGMBoissonsDispo;
        $tab["frites"] = $this->friterepo->findAll();
        return  $tab;    
    }
}
