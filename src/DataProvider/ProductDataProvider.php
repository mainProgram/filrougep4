<?php
#

namespace App\DataProvider;

use App\Entity\Burger;
use App\Entity\Produit;
use App\Repository\BurgerRepository;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use App\Entity\Boisson;
use App\Repository\BoissonRepository;

class ProductDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(BurgerRepository $bgrepo, BoissonRepository $repo)
    {
        $this->repo = $repo;
        $this->bgrepo = $bgrepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        // if( $resourceClass == Burger::class)
        //     return $this->bgrepo->findBy(["isEtat" => 1]);
        // else
        //     return $this->repo->findBy(["isEtat" => 1]);

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        // return  $resourceClass == Burger::class or  $resourceClass == Boisson::class;
        return  $resourceClass == "";
    }
    
}