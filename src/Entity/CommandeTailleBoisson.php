<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommandeTailleBoissonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeTailleBoissonRepository::class)]
class CommandeTailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeTailleBoissons')]
    private $commande;

    #[Assert\NotNull(message: "Renseigner une boisson!")]
    #[ORM\ManyToOne(targetEntity: TailleBoisson::class, inversedBy: 'commandeTailleBoissons')]
    private $tailleBoisson;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $prix;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTailleBoisson(): ?TailleBoisson
    {
        return $this->tailleBoisson;
    }

    public function setTailleBoisson(?TailleBoisson $tailleBoisson): self
    {
        $this->tailleBoisson = $tailleBoisson;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
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
}
