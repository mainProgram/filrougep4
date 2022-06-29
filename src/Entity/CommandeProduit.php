<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Console\Command\Command;

#[ORM\Entity]
class CommandeProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[ORM\Column(type: 'float')]
    private $quantite;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: "commandeProduits")]
    private $produit;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "commandeProduits")]
    private $commande;


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
     * Get the value of quantite
     */ 
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set the value of quantite
     *
     * @return  self
     */ 
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get the value of commande
     */ 
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set the value of commande
     *
     * @return  self
     */ 
    public function setCommande($commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get the value of produit
     */ 
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set the value of produit
     *
     * @return  self
     */ 
    public function setProduit($produit)
    {
        $this->produit = $produit;

        return $this;
    }
}
