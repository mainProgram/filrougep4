<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Taille;
use App\Entity\Boisson;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
/**
 * @ORM\Table(name="taille_boisson")
 */
#[ApiResource(
    // collectionOperations:[
    //     "get" => [
    //         "method" => "get",
    //         "status" => 200,
    //         "normalization_context" => [
    //             "groups" => ["taille_boisson:read"]
    //         ]
    //     ], "post"
    // ],
    // itemOperations:[
    //     "get"
    // ] 
    collectionOperations:[
        "get" => [
            "openapi_context" => ["summary"=>"hidden"]
        ]
    ] ,
    itemOperations:[
        "get" => [
            "openapi_context" => ["summary"=>"hidden"]
        ]
    ]
)]
class TailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["menu:write"])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups([ "taille_boisson:read"])]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[Assert\NotNull(message: "Renseignez la quantité en stock !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[Groups(["boisson:write"])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantiteStock;

    #[Groups(["boisson:write", "taille_boisson:read"])]
    #[Assert\NotNull(message: "Renseignez la taille !")]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'tailleBoissons')]
    private $taille;

    #[Assert\NotNull(message: "Renseignez la boisson !")]
    #[Groups(["taille_boisson:read"])]
    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'tailleBoissons')]
    private $boisson;

    #[ORM\OneToMany(mappedBy: 'tailleBoisson', targetEntity: CommandeTailleBoisson::class)]
    private $commandeTailleBoissons;

    #[ORM\ManyToMany(targetEntity: CommandeProduit::class, mappedBy: 'tailleBoissons')]
    private $commandeProduits;

    public function __construct()
    {
        $this->commandeTailleBoissons = new ArrayCollection();
        $this->commandeProduits = new ArrayCollection();
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

    public function getQuantiteStock(): ?int
    {
        return $this->quantiteStock;
    }

    public function setQuantiteStock(?int $quantiteStock): self
    {
        $this->quantiteStock = $quantiteStock;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getBoisson(): ?Boisson
    {
        return $this->boisson;
    }

    public function setBoisson(?Boisson $boisson): self
    {
        $this->boisson = $boisson;

        return $this;
    }

    /**
     * @return Collection<int, CommandeTailleBoisson>
     */
    public function getCommandeTailleBoissons(): Collection
    {
        return $this->commandeTailleBoissons;
    }

    public function addCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson): self
    {
        if (!$this->commandeTailleBoissons->contains($commandeTailleBoisson)) {
            $this->commandeTailleBoissons[] = $commandeTailleBoisson;
            $commandeTailleBoisson->setTailleBoisson($this);
        }

        return $this;
    }

    public function removeCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson): self
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
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->addTailleBoisson($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            $commandeProduit->removeTailleBoisson($this);
        }

        return $this;
    }

   
}