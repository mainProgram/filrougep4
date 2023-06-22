<?php

namespace App\State;

use App\Repository\MenuRepository;
use ApiPlatform\Metadata\Operation;
use App\Repository\FriteRepository;
use App\Repository\BurgerRepository;
use ApiPlatform\State\ProviderInterface;
use App\Repository\TailleBoissonRepository;

class CatalogueProvider implements ProviderInterface
{
    public function __construct(BurgerRepository $bgrepo, MenuRepository $menurepo, FriteRepository $friterepo, TailleBoissonRepository $tbrepo)
    {
        $this->menurepo = $menurepo;
        $this->bgrepo = $bgrepo;
        $this->friterepo = $friterepo;
        $this->tbrepo = $tbrepo;
    }
    
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $tab["burgers"] = $this->bgrepo->findBy(["isEtat" => 1]);
        $tab["menus"] = $this->menurepo->findBy(["isEtat" => 1]);     
        $tab["frites"] = $this->friterepo->findBy(["isEtat" => 1]);     
        $tab["boissons"] = $this->tbrepo->findBy(["isEtat" => true]);     
        return  $tab;
    }
}
