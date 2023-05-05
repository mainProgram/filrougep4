<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Entity\Taille;
use App\Entity\Boisson;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(
    operations: [
        new Get(), 
        new Put(), 
        new GetCollection(normalizationContext: ['groups' => ['complement:read']]), 
        new Post(denormalizationContext: ['groups' => ['taille_boisson:write']])
    ]
)]
#[ORM\Entity]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['nom' => 'ipartial', 'isEtat' => 'exact'])]
class TailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["menu:write", "complement:read", "commande:read"])]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Groups(["complement:read", "commande:client:detail", "taille_boisson:write"])]
    #[ORM\Column(type: 'float')]
    private $prix;
    #[Assert\NotNull(message: "Renseignez la quantité en stock !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[Groups(["taille_boisson:write", "complement:read"])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantiteStock;
    #[Groups(["taille_boisson:write", "complement:read"])]
    #[Assert\NotNull(message: "Renseignez la taille !")]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'tailleBoissons')]
    private $taille;
    #[Assert\NotNull(message: "Renseignez la boisson !")]
    #[Groups(["taille_boisson:write", "complement:read"])]
    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'tailleBoissons')]
    private $boisson;
    #[ORM\OneToMany(mappedBy: 'tailleBoisson', targetEntity: CommandeTailleBoisson::class)]
    private $commandeTailleBoissons;
    #[ORM\OneToMany(mappedBy: 'tailleBoisson', targetEntity: CommandeMenuTailleBoisson::class)]
    private $commandeMenuTailleBoissons;
    #[Groups(["complement:read", "commande:client:detail"])]
    #[ORM\Column(type: 'blob', nullable: true)]
    private $image;
    #[Groups(["taille_boisson:write"])]
    private $imageWrapper;
    #[Groups(["complement:read", "commande:client:detail"])]
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $nom;
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isEtat = true;
    public function __construct()
    {
        $this->commandeTailleBoissons = new ArrayCollection();
        $this->commandeMenuTailleBoissons = new ArrayCollection();
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
    public function getQuantiteStock() : ?int
    {
        return $this->quantiteStock;
    }
    public function setQuantiteStock(?int $quantiteStock) : self
    {
        $this->quantiteStock = $quantiteStock;
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
    public function getBoisson() : ?Boisson
    {
        return $this->boisson;
    }
    public function setBoisson(?Boisson $boisson) : self
    {
        $this->boisson = $boisson;
        return $this;
    }
    /**
     * @return Collection<int, CommandeTailleBoisson>
     */
    public function getCommandeTailleBoissons() : Collection
    {
        return $this->commandeTailleBoissons;
    }
    public function addCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson) : self
    {
        if (!$this->commandeTailleBoissons->contains($commandeTailleBoisson)) {
            $this->commandeTailleBoissons[] = $commandeTailleBoisson;
            $commandeTailleBoisson->setTailleBoisson($this);
        }
        return $this;
    }
    public function removeCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson) : self
    {
        if ($this->commandeTailleBoissons->removeElement($commandeTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($commandeTailleBoisson->getTailleBoisson() === $this) {
                $commandeTailleBoisson->setTailleBoisson(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, CommandeMenuTailleBoisson>
     */
    public function getCommandeMenuTailleBoissons() : Collection
    {
        return $this->commandeMenuTailleBoissons;
    }
    public function addCommandeMenuTailleBoisson(CommandeMenuTailleBoisson $commandeMenuTailleBoisson) : self
    {
        if (!$this->commandeMenuTailleBoissons->contains($commandeMenuTailleBoisson)) {
            $this->commandeMenuTailleBoissons[] = $commandeMenuTailleBoisson;
            $commandeMenuTailleBoisson->setTailleBoisson($this);
        }
        return $this;
    }
    public function removeCommandeMenuTailleBoisson(CommandeMenuTailleBoisson $commandeMenuTailleBoisson) : self
    {
        if ($this->commandeMenuTailleBoissons->removeElement($commandeMenuTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenuTailleBoisson->getTailleBoisson() === $this) {
                $commandeMenuTailleBoisson->setTailleBoisson(null);
            }
        }
        return $this;
    }
    public function getImage()
    {
        return is_resource($this->image) ? base64_encode(stream_get_contents($this->image)) : $this->image;
    }
    public function setImage($image) : self
    {
        $this->image = $image;
        return $this;
    }
    /**
     * Get the value of imageWrapper
     */
    public function getImageWrapper()
    {
        return $this->imageWrapper;
    }
    /**
     * Set the value of imageWrapper
     *
     * @return  self
     */
    public function setImageWrapper($imageWrapper)
    {
        $this->imageWrapper = $imageWrapper;
        return $this;
    }
    public function getNom() : ?string
    {
        return $this->nom;
    }
    public function setNom(?string $nom) : self
    {
        $this->nom = $nom;
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