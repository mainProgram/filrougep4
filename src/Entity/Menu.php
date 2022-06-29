<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [
                "groups" => ["menu:list"]
            ]
        ],
        "post" => [
            "method" => "post",
            "status" => 201,
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ]
    ],
    itemOperations:[
        "put" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ],
        "delete" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ],
        "get" => [
            "normalization_context" => [
                "groups" => ["menu:detail", ]
            ]
        ]
    ]
)]
#[ApiFilter(PropertyFilter::class)]
class Menu extends Produit
{
    #[ORM\ManyToMany(targetEntity: Frite::class, inversedBy: 'menus')]
    #[Groups(["menu:detail"])]
    private $frites;

    #[ORM\ManyToMany(targetEntity: Burger::class, inversedBy: 'menus')]
    #[Groups(["menu:detail"])]
    private $burgers;

    #[ORM\ManyToMany(targetEntity: Taille::class, inversedBy: 'menus')]
    private $tailles;

    #[Groups(["menu:detail"])]
    #[ORM\ManyToMany(targetEntity: TailleBoisson::class, inversedBy: 'menus')]
    private $tailleBoissons;

    public function __construct()
    {
        parent::__construct();
        $this->frites = new ArrayCollection();
        $this->burgers = new ArrayCollection();
        $this->tailles = new ArrayCollection();
        $this->tailleBoissons = new ArrayCollection();
    }

    /**
     * @return Collection<int, Frite>
     */
    public function getFrites(): Collection
    {
        return $this->frites;
    }

    public function addFrite(Frite $frite): self
    {
        if (!$this->frites->contains($frite)) {
            $this->frites[] = $frite;
        }

        return $this;
    }

    public function removeFrite(Frite $frite): self
    {
        $this->frites->removeElement($frite);

        return $this;
    }

    /**
     * @return Collection<int, Burger>
     */
    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        $this->burgers->removeElement($burger);

        return $this;
    }

    // /**
    //  * @return Collection<int, TailleBoisson>
    //  */
    // public function getTailleBoissons(): Collection
    // {
    //     return $this->tailleBoissons;
    // }

    // public function addTailleBoisson(TailleBoisson $tailleBoisson): self
    // {
    //     if (!$this->tailleBoissons->contains($tailleBoisson)) {
    //         $this->tailleBoissons[] = $tailleBoisson;
    //     }

    //     return $this;
    // }

    // public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    // {
    //     $this->tailleBoissons->removeElement($tailleBoisson);

    //     return $this;
    // }

    /**
     * @return Collection<int, Taille>
     */
    public function getTailles(): Collection
    {
        return $this->tailles;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->tailles->contains($taille)) {
            $this->tailles[] = $taille;
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        $this->tailles->removeElement($taille);

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
