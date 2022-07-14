<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommandeProduitRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandeProduitRepository::class)]
class CommandeProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(["commande:list"])]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantite = 1 ;

    #[ORM\Column(type: 'float', nullable: true)]
    private $prix;

    #[Groups(["commande:list"])]
    #[Assert\NotNull(message: "Renseigner un produit !")]
    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'commandeProduits')]
    private $produit;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeProduits')]
    private $commande;

    #[ORM\ManyToMany(targetEntity: TailleBoisson::class, inversedBy: 'commandeProduits')]
    private $tailleBoissons;

    public function __construct()
    {
        $this->tailleBoissons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * @return Collection<int, TailleBoisson>
     */
    public function getTailleBoissons(): Collection
    {
        return $this->tailleBoissons;
    }

    public function addTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if (!$this->tailleBoissons->contains($tailleBoisson)) {
            $this->tailleBoissons[] = $tailleBoisson;
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        $this->tailleBoissons->removeElement($tailleBoisson);

        return $this;
    }

  
}
