<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeFriteRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeFriteRepository::class)]
class CommandeFrite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Groups(["commande:client:detail"])]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    private $quantite = 1;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $prix;

    #[Assert\NotNull(message: "Renseigner des frites !")]
    #[Groups(["commande:client:detail"])]
    #[ORM\ManyToOne(targetEntity: Frite::class, inversedBy: 'commandeFrites')]
    private $frite;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeFrites', cascade: ["persist"])]
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getFrite(): ?Frite
    {
        return $this->frite;
    }

    public function setFrite(?Frite $frite): self
    {
        $this->frite = $frite;

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
}
