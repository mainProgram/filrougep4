<?php

namespace App\Entity;

use App\Entity\Livreur;
use App\Entity\Commande;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['livraison:detail']]), 
        new Put(), 
        new GetCollection(normalizationContext: ['groups' => ['livraison:read']]), 
        new Post()
    ]
)]
#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
#[ApiResource(
    uriTemplate: '/livreurs/{id}/livraisons.{_format}', 
    uriVariables: ['id' => new Link(fromClass: Livreur::class, fromProperty: 'livraisons')], 
    status: 200, 
    operations: [new GetCollection()],
    normalizationContext: ['groups' => ['livraison:detail']]
)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["livraison:read", "livraison:detail", "livreur:detail"])]
    private $id;
    #[ORM\Column(type: 'time', nullable: true)]
    private $duree;
    #[Groups(["livraison:read", "livraison:detail", "livreur:detail"])]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date;
    #[Groups(["livraison:read", "livraison:detail"])]
    #[ORM\ManyToOne(targetEntity: Livreur::class, inversedBy: 'livraisons')]
    private $livreur;
    #[Groups(["livraison:detail"])]
    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Commande::class, cascade: ["persist"])]
    #[Assert\Count(min: 1, minMessage: "Renseigner les commandes !")]
    private $commandes;
    #[Groups(["livraison:read", "livraison:detail", "livreur:detail"])]
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $etat = "en cours";
    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->date = new \DateTime();
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getDuree() : ?\DateTimeInterface
    {
        return $this->duree;
    }
    public function setDuree(?\DateTimeInterface $duree) : self
    {
        $this->duree = $duree;
        return $this;
    }
    public function getDate() : ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(?\DateTimeInterface $date) : self
    {
        $this->date = $date;
        return $this;
    }
    public function getLivreur() : ?Livreur
    {
        return $this->livreur;
    }
    public function setLivreur(?Livreur $livreur) : self
    {
        $this->livreur = $livreur;
        return $this;
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
            $commande->setLivraison($this);
        }
        return $this;
    }
    public function removeCommande(Commande $commande) : self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getLivraison() === $this) {
                $commande->setLivraison(null);
            }
        }
        return $this;
    }
    public function getEtat() : ?string
    {
        return $this->etat;
    }
    public function setEtat(?string $etat) : self
    {
        $this->etat = $etat;
        return $this;
    }
}
