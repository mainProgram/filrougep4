<?php
#

namespace App\DataProvider;
use App\Entity\Complement;
use App\Repository\TailleBoissonRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use App\Entity\TailleBoisson;
use App\Repository\FriteRepository;
use Doctrine\ORM\Query\Expr\From;
use Egulias\EmailValidator\Warning\TLD;

class ComplementDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct(FriteRepository $friterepo, TailleBoissonRepository $tbrepo)
    {
        $this->tbrepo = $tbrepo;
        $this->friterepo = $friterepo;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $tab[] = $this->friterepo->findAll();
        $tab[] = $this->tbrepo->findAll();     
        return  $tab;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return  $resourceClass == Complement::class;
    }
    
}