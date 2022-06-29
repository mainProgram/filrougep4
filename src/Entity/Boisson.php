<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BoissonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoissonRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get" => [
            "method" => "get",
            "status" => 200,
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [
                "groups" => ["burger:list"]
            ]
        ],
        "post" => [
            "status" => 201,
            "method" => "post",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [
                "groups" => ["burger:detail"]
            ]
        ],
    ],
    itemOperations: [
        "get" => [
            "method" => "get",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:detail"]
            ]
        ],
        "delete" => [
            "method" => "delete",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ],
        "put" => [
            "method" => "put",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ]
    ]
)]
class Boisson extends Produit
{

    #[ORM\OneToMany(targetEntity: TailleBoisson::class, mappedBy: 'boisson')]
    private $tailleBoissons;

    public function __construct()
    {
        parent::__construct();
        $this->tailleBoissons = new ArrayCollection();
        $this->menus = new ArrayCollection();
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
}
