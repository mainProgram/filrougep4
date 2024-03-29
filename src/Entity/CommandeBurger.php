<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeBurgerRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeBurgerRepository::class)]
class CommandeBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')] 
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[Groups(["commande:client:detail"])]
    private $quantite = 1;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $prix;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeBurgers', cascade: ["persist"])]
    private $commande;

    #[Assert\NotNull(message: "Renseigner un burger !")]
    #[Groups(["commande:client:detail"])]
    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'commandeBurgers')]
    private $burger;

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

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }
}
