<?php

namespace App\Entity;

use App\Entity\Commande;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
#[ApiResource(
    operations: [
        new Get(), 
        new Post(status: 201, uriTemplate: '/sign_up', denormalizationContext: ['groups' => ['sign_up:write']], normalizationContext: ['groups' => ['sign_up:read']]), 
        new GetCollection()
    ]
)]
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{
    // #[ApiSubresource]
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Commande::class)]
    private $commandes;
    public function __construct()
    {
        parent::__construct();
        $this->commandes = new ArrayCollection();
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
            $commande->setClient($this);
        }
        return $this;
    }
    public function removeCommande(Commande $commande) : self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }
        return $this;
    }
}
