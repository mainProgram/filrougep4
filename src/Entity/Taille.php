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
    #[Groups(["menu:write"])]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(["taille_boisson", "menu:detail"])]
    private $nom;

    #[ORM\OneToMany(targetEntity: TailleBoisson::class, mappedBy:"taille")]
    private $tailleBoissons;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $prix;

    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: MenuTaille::class)]
    private $menuTailles;

    public function __construct()
    {
        $this->tailleBoissons = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
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


    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, MenuTaille>
     */
    public function getMenuTailles(): Collection
    {
        return $this->menuTailles;
    }

    public function addMenuTaille(MenuTaille $menuTaille): self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setTaille($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getTaille() === $this) {
                $menuTaille->setTaille(null);
            }
        }

        return $this;
    }

    
}
