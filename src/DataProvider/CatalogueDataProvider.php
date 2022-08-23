<?php
#

namespace App\DataProvider;
use App\Entity\Catalogue;
use App\Repository\MenuRepository;
use App\Repository\FriteRepository;
use App\Repository\BurgerRepository;
use App\Repository\TailleBoissonRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class CatalogueDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(BurgerRepository $bgrepo, MenuRepository $menurepo, FriteRepository $friterepo, TailleBoissonRepository $tbrepo)
    {
        $this->menurepo = $menurepo;
        $this->bgrepo = $bgrepo;
        $this->friterepo = $friterepo;
        $this->tbrepo = $tbrepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $tab["burgers"] = $this->bgrepo->findBy(["isEtat" => 1]);
        $tab["menus"] = $this->menurepo->findBy(["isEtat" => 1]);     
        $tab["frites"] = $this->friterepo->findBy(["isEtat" => 1]);     
        $tab["boissons"] = $this->tbrepo->findBy(["isEtat" => 1]);     
        return  $tab;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == Catalogue::class;
    }
    
}