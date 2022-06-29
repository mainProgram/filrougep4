<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ZoneRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ApiResource(
    
)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[ORM\Column(type: 'string', length: 20)]
    private $nom;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[ORM\Column(type: 'float')]
    private $prix;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
