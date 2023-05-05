<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuBurgerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
#[ApiResource(operations: [new Get(openapiContext: ['summary' => 'hidden']), new GetCollection(openapiContext: ['summary' => 'hidden'])])]
#[ORM\Entity(repositoryClass: MenuBurgerRepository::class)]
class MenuBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Groups(["menu:write", "produit:detail"])]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantite = 1;
    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'menuBurgers')]
    #[Assert\NotNull(message: "Renseignez le burger !")]
    #[Groups(["menu:write", "produit:detail"])]
    private $burger;
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuBurgers')]
    private $menu;
    public function __construct()
    {
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getQuantite() : ?int
    {
        return $this->quantite;
    }
    public function setQuantite(?int $quantite) : self
    {
        $this->quantite = $quantite;
        return $this;
    }
    public function getBurger() : ?Burger
    {
        return $this->burger;
    }
    public function setBurger(?Burger $burger) : self
    {
        $this->burger = $burger;
        return $this;
    }
    public function getMenu() : ?Menu
    {
        return $this->menu;
    }
    public function setMenu(?Menu $menu) : self
    {
        $this->menu = $menu;
        return $this;
    }
}
