<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type:"string")]
#[ORM\DiscriminatorMap(["produit" => "Produit", "burger" => "Burger"])]
#[ApiResource(
    subresourceOperations: [
        "api_users_produits_get_subresource" => [
            "method" => "GET",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:list"]
            ]
        ]
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['type' => 'exact' ])]
#[ApiFilter(NumericFilter::class, properties: ['prix'])]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["burger:detail", "burger:list"])]
    protected $id;

    #[Groups(["burger:detail", "burger:list"])]
    #[ORM\Column(type: 'string', length: 50)]
    protected $nom;

    #[Groups(["burger:detail", "burger:list"])]
    #[ORM\Column(type: 'float')]
    protected $prix;

    #[Groups(["burger:detail"])]
    #[ORM\Column(type: 'object')]
    protected $image;

    #[Groups(["burger:detail"])]
    #[ORM\Column(type: 'boolean')]
    protected $isEtat = 1;

    #[Groups(["burger:detail"])]
    #[ORM\Column(type: 'string', length: 255)]
    protected $detail = "";

    #[Groups(["burger:detail"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

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

    public function getImage(): ?object
    {
        return $this->image;
    }

    public function setImage(object $image): self
    {
        $this->image = $image;

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
