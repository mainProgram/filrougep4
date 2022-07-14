<?php
#

namespace App\DataProvider;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class ArchivesDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(ProduitRepository $produitrepo)
    {
        $this->produitrepo = $produitrepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    { 
        $tab[] = $this->produitrepo->findBy(["isEtat" => 0]);
        return  $tab;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == Produit::class;
    }
    
}