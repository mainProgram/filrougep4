<?php

namespace App\Entity;

use App\Entity\Produit;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use App\Entity\TailleBoisson;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\BoissonRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
#[ApiResource(
    operations: [
        new Get(status: 200, normalizationContext: ['groups' => ['burger:detail']]), 
        new Put(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new Delete(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new GetCollection(status: 200, security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['boisson:read']]), 
        new Post(status: 201, security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['produit:detail']], denormalizationContext: ['groups' => ['boisson:write']])])]
#[ORM\Entity(repositoryClass: BoissonRepository::class)]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['nom' => 'ipartial', 'isEtat' => 'exact'])]
class Boisson extends Produit
{
    #[ORM\OneToMany(mappedBy: 'boisson', targetEntity: TailleBoisson::class, cascade: ["persist"])]
    private $tailleBoissons;
    public function __construct()
    {
        parent::__construct();
        $this->tailleBoissons = new ArrayCollection();
        $this->categorie = "boisson";
    }
    /**
     * @return Collection<int, TailleBoisson>
     */
    public function getTailleBoissons() : Collection
    {
        return $this->tailleBoissons;
    }
    public function addTailleBoisson(TailleBoisson $tailleBoisson) : self
    {
        if (!$this->tailleBoissons->contains($tailleBoisson)) {
            $this->tailleBoissons[] = $tailleBoisson;
            $tailleBoisson->setBoisson($this);
        }
        return $this;
    }
    public function removeTailleBoisson(TailleBoisson $tailleBoisson) : self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getBoisson() === $this) {
                $tailleBoisson->setBoisson(null);
            }
        }
        return $this;
    }
}
