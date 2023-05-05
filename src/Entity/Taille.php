<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(
    operations: [
        new Get(), 
        new Put(), 
        new Delete(), 
        new Post(denormalizationContext: ['groups' => ['taille:write']]), 
        new GetCollection(normalizationContext: ['groups' => ['taille:read']])
    ]
)]
#[ORM\Entity(repositoryClass: TailleRepository::class)]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['nom' => 'ipartial', 'isEtat' => 'exact'])]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["taille:read"])]
    private $id;
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[ORM\Column(type: 'string', length: 20, unique: true)]
    #[Groups(["complement:read", "menu:detail", "taille:read", "taille:write", "produit:detail"])]
    private $nom;
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[Groups(["taille:read", "taille:write"])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $prix;
    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: MenuTaille::class)]
    private $menuTailles;
    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: TailleBoisson::class)]
    private $tailleBoissons;
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isEtat;
    public function __construct()
    {
        $this->menuTailles = new ArrayCollection();
        $this->tailleBoissons = new ArrayCollection();
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getNom() : ?string
    {
        return $this->nom;
    }
    public function setNom(string $nom) : self
    {
        $this->nom = $nom;
        return $this;
    }
    public function getPrix() : ?int
    {
        return $this->prix;
    }
    public function setPrix(?int $prix) : self
    {
        $this->prix = $prix;
        return $this;
    }
    /**
     * @return Collection<int, MenuTaille>
     */
    public function getMenuTailles() : Collection
    {
        return $this->menuTailles;
    }
    public function addMenuTaille(MenuTaille $menuTaille) : self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setTaille($this);
        }
        return $this;
    }
    public function removeMenuTaille(MenuTaille $menuTaille) : self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getTaille() === $this) {
                $menuTaille->setTaille(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, TailleBoisson>
     */
    public function getTailleBoissons() : Collection
    {
        return $this->tailleBoissons;
    }
    public function addTailleBoisson(TailleBoisson $tailleBoisson) : self
    {
        if (!$this->tailleBoissons->contains($tailleBoisson)) {
            $this->tailleBoissons[] = $tailleBoisson;
            $tailleBoisson->setTaille($this);
        }
        return $this;
    }
    public function removeTailleBoisson(TailleBoisson $tailleBoisson) : self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getTaille() === $this) {
                $tailleBoisson->setTaille(null);
            }
        }
        return $this;
    }
    public function isIsEtat() : ?bool
    {
        return $this->isEtat;
    }
    public function setIsEtat(?bool $isEtat) : self
    {
        $this->isEtat = $isEtat;
        return $this;
    }
}
