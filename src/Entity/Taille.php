<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: TailleRepository::class)]
#[ApiResource()]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(["taille_boisson", "menu:detail"])]
    private $nom;

    #[ORM\OneToMany(targetEntity: TailleBoisson::class, mappedBy:"taille")]
    private $tailleBoissons;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'tailles')]
    private $menus;

    public function __construct()
    {
        $this->tailleBoissons = new ArrayCollection();
        $this->menus = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }


    /**
     * Get the value of tailleBoissons
     */ 
    public function getTailleBoissons()
    {
        return $this->tailleBoissons;
    }

    /**
     * Set the value of tailleBoissons
     *
     * @return  self
     */ 
    public function setTailleBoissons($tailleBoissons)
    {
        $this->tailleBoissons = $tailleBoissons;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addTaille($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeTaille($this);
        }

        return $this;
    }
}
