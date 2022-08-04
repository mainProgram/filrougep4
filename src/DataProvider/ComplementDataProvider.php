<?php
#

namespace App\DataProvider;
use App\Entity\Complement;
use App\Repository\TailleBoissonRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use App\Repository\FriteRepository;

class ComplementDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(FriteRepository $friterepo, TailleBoissonRepository $tbrepo)
    {
        $this->tbrepo = $tbrepo;
        $this->friterepo = $friterepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $qb = $this->tbrepo->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', 1)->andWhere('tbrepo.quantiteStock > 0');
        $query = $qb->getQuery();
        $lesTaillesPMBoissonsDispo = $query->execute();

        $qb = $this->tbrepo->createQueryBuilder('tbrepo')->where('tbrepo.taille = :taille')->setParameter('taille', 2)->andWhere('tbrepo.quantiteStock > 0');
        $query = $qb->getQuery();
        $lesTaillesGMBoissonsDispo = $query->execute();

        // $qb = $this->tbrepo->createQueryBuilder('tbrepo')->where('tbrepo.quantiteStock > 0');
        // $query = $qb->getQuery();
        // $boissons = $query->execute();


        $tab["pm"] = $lesTaillesPMBoissonsDispo;
        $tab["gm"] = $lesTaillesGMBoissonsDispo;
        $tab["frites"] = $this->friterepo->findAll();
        // $tab["boissons"] = $boissons;
        return  $tab;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == Complement::class;
    }
    
}