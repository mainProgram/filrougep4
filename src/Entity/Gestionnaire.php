<?php

namespace App\Entity;

use App\Entity\Commande;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GestionnaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
#[ApiResource(operations: [new Get(openapiContext: ['summary' => 'hidden']), new GetCollection(openapiContext: ['summary' => 'hidden'])])]
#[ORM\Entity(repositoryClass: GestionnaireRepository::class)]
class Gestionnaire extends User
{
    #[ORM\OneToMany(mappedBy: 'gestionnaire', targetEntity: Commande::class)]
    private $commandes;
    public function __construct()
    {
        parent::__construct();
        $this->commandes = new ArrayCollection();
    }
    public function getRole() : string
    {
        return "ROLE_GESTIONNAIRE";
    }
    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes() : Collection
    {
        return $this->commandes;
    }
    public function addCommande(Commande $commande) : self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setGestionnaire($this);
        }
        return $this;
    }
    public function removeCommande(Commande $commande) : self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getGestionnaire() === $this) {
                $commande->setGestionnaire(null);
            }
        }
        return $this;
    }
}
