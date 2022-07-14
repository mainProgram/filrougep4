<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuartierRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuartierRepository::class)]
#[ApiResource(
    security : "is_granted('ROLE_GESTIONNAIRE')",
    securityMessage : "Vous n'êtes pas autorisé !",
    collectionOperations: [
        "get" => [
            "normalization_context" => ["groups" => ["quartier:read"]  ]
        ],
        "post" => [
            "denormalization_context" => ["groups" => ["quartier:write"]  ]
        ]
    ]
)]
class Quartier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Groups(["zone:read", "quartier:read", "quartier:write"])]
    private $nom;

    #[ORM\Column(type: 'boolean')]
    private $isEtat = true;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'quartiers')]
    #[Groups(["quartier:write"])]
    #[ORM\JoinColumn(nullable: false)]
    private $zone;

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

    public function isIsEtat(): ?bool
    {
        return $this->isEtat;
    }

    public function setIsEtat(bool $isEtat): self
    {
        $this->isEtat = $isEtat;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}
