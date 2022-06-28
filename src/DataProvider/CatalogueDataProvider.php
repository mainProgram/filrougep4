<?php
#

namespace App\DataProvider;

use App\Entity\Burger;
use App\Entity\Produit;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use App\Repository\MenuRepository;

class ProductDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(BurgerRepository $bgrepo, MenuRepository $menurepo)
    {
        $this->menurepo = $menurepo;
        $this->bgrepo = $bgrepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $tab[] = $this->bgrepo->findBy(["isEtat" => 1]);
        $tab[] = $this->repo->findBy(["isEtat" => 1]);
             
            return  $tab;

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == Burger::class or  $resourceClass == Menu::class;
    }
    
}