<?php

namespace App\Entity;

use App\Entity\Zone;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuartierRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(
    security: "is_granted('ROLE_GESTIONNAIRE')", 
    securityMessage: 'Vous n\'êtes pas autorisé !',
    operations: [
        new Get(), 
        new Put(), 
        new Patch(), 
        new Delete(), 
        new GetCollection(normalizationContext: ['groups' => ['quartier:read']]), 
        new Post(denormalizationContext: ['groups' => ['quartier:write']])
    ]
)]
#[ORM\Entity(repositoryClass: QuartierRepository::class)]
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
    public function isIsEtat() : ?bool
    {
        return $this->isEtat;
    }
    public function setIsEtat(bool $isEtat) : self
    {
        $this->isEtat = $isEtat;
        return $this;
    }
    public function getZone() : ?Zone
    {
        return $this->zone;
    }
    public function setZone(?Zone $zone) : self
    {
        $this->zone = $zone;
        return $this;
    }
}
