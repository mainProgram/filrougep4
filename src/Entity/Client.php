<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    collectionOperations:[
        "sign_up" => [
            "method" => "post",
            "status" => 201,
            "path" => "/sign_up",
            "denormalization_context" => [ "groups" => ["sign_up:write"]],
            "normalization_context" => ["groups" => ["sign_up:read"]]
        ]
        ],
    itemOperations:[
        "get" 
    ]
)]
class Client extends User
{
    #[ApiSubresource()]
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Commande::class)]
    #[Groups(["commande:client:read"])]
    private $commandes;

    public function __construct()
    {
        parent::__construct();
        $this->commandes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
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
