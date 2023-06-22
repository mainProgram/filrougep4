<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Taille;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\MenuTailleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(operations: [new Get(openapiContext: ['summary' => 'hidden']), new GetCollection(openapiContext: ['summary' => 'hidden'])])]
#[ORM\Entity(repositoryClass: MenuTailleRepository::class)]
class MenuTaille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Groups(["menu:write", "produit:detail"])]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantite = 1;
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuTailles')]
    private $menu;
    #[Groups(["menu:write", "produit:detail"])]
    #[Assert\NotNull(message: "Renseignez la taille !")]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'menuTailles')]
    private $taille;
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
    public function getMenu() : ?Menu
    {
        return $this->menu;
    }
    public function setMenu(?Menu $menu) : self
    {
        $this->menu = $menu;
        return $this;
    }
    public function getTaille() : ?Taille
    {
        return $this->taille;
    }
    public function setTaille(?Taille $taille) : self
    {
        $this->taille = $taille;
        return $this;
    }
}
