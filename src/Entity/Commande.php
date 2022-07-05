<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    private $client;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'commandes')]
    private $gestionnaire;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    private $zone;

    #[ORM\OneToOne(targetEntity: Ticket::class, cascade: ['persist', 'remove'])]
    private $ticket;

    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'commande')]
    private $commandeProduits;

    #[ORM\Column(type: 'string', length: 10)]
    private $etat;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $paye;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    // /**
    //  * @return Collection<int, Produit>
    //  */
    // public function getCommandeProduits(): Collection
    // {
    //     return $this->commandeProduits;
    // }

    // public function addCommandeProduits(Produit $produit): self
    // {
    //     if (!$this->commandeProduits->contains($produit)) {
    //         $this->commandeProduits[] = $produit;
    //     }

    //     return $this;
    // }

    // public function removeCommandeProduits(Produit $produit): self
    // {
    //     $this->commandeProduits->removeElement($produit);

    //     return $this;
    // }

    /**
     * Get the value of commandeProduits
     */ 
    public function getCommandeProduits()
    {
        return $this->commandeProduits;
    }

    /**
     * Set the value of commandeProduits
     *
     * @return  self
     */ 
    public function setCommandeProduits($commandeProduits)
    {
        $this->commandeProduits = $commandeProduits;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(?bool $paye): self
    {
        $this->paye = $paye;

        return $this;
    }
}
