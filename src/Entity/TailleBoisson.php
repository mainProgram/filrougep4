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

    #[Groups(["boisson:write", "taille_boisson"])]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[Groups(["boisson:write"])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantiteStock;

    #[Groups(["boisson:write"])]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'tailleBoissons')]
    private $taille;

    #[ORM\ManyToOne(targetEntity: Boisson::class, inversedBy: 'tailleBoissons')]
    private $boisson;

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


   
}