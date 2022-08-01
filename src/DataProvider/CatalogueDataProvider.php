<?php
#

namespace App\DataProvider;
use App\Entity\Catalogue;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class CatalogueDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(BurgerRepository $bgrepo, MenuRepository $menurepo)
    {
        $this->menurepo = $menurepo;
        $this->bgrepo = $bgrepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $tab["burgers"] = $this->bgrepo->findBy(["isEtat" => 1]);
        $tab["menus"] = $this->menurepo->findBy(["isEtat" => 1]);     
        return  $tab;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == Catalogue::class;
    }
    
}