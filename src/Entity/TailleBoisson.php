<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Taille;
use App\Entity\Boisson;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
/**
 * @ORM\Table(name="taille_boisson")
 */
#[ApiResource(
    collectionOperations:[
        "get" => [
            "method" => "get",
            "status" => 200,
            // "normalization_context" => [
            //     "groups" => ["taille_boisson"]
            // ]
        ], "post"
    ]
)]
class TailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["menu:write"])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(["taille_boisson"])]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[Groups(["taille_boisson", "menu:detail"])]
    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: "tailleBoissons")]
    private $boisson;

    #[Groups(["taille_boisson", "menu:detail"])]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: "tailleBoissons")]
    private $taille;

    public function __construct()
    {
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of prix
     */ 
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set the value of prix
     *
     * @return  self
     */ 
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get the value of boisson
     */ 
    public function getBoisson()
    {
        return $this->boisson;
    }

    /**
     * Set the value of boisson
     *
     * @return  self
     */ 
    public function setBoisson($boisson)
    {
        $this->boisson = $boisson;

        return $this;
    }

    /**
     * Get the value of taille
     */ 
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set the value of taille
     *
     * @return  self
     */ 
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    // /**
    //  * @return Collection<int, Menu>
    //  */
    // public function getMenus(): Collection
    // {
    //     return $this->menus;
    // }

    // public function addMenu(Menu $menu): self
    // {
    //     if (!$this->menus->contains($menu)) {
    //         $this->menus[] = $menu;
    //         $menu->addTailleBoisson($this);
    //     }

    //     return $this;
    // }

    // public function removeMenu(Menu $menu): self
    // {
    //     if ($this->menus->removeElement($menu)) {
    //         $menu->removeTailleBoisson($this);
    //     }

    //     return $this;
    // }


   
}