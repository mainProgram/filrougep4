<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\ProduitController;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type:"string")]
#[ORM\DiscriminatorMap(["produit" => "Produit", "burger" => "Burger",  "frite" => "Frite",  "boisson" => "Boisson", "menu" => "Menu"])]
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial', "type" => "exact" ])]
#[ApiFilter(NumericFilter::class, properties: ['prix'])]
#[ApiResource(
    collectionOperations: [
        // "complements" => [
        //     "status" => 200,
        //     "path" => "/complements",
        //     "controller" => ProduitController::class,
        //     "route_name" => "complements"
        // ],
        // "catalogue" => [
        //     "status" => 200,
        //     "path" => "/catalogue",
        //     "controller" => ProduitController::class,
        //     "route_name" => "catalogue"
        // ],
        "get"
    ],
    subresourceOperations: [
        "api_users_produits_get_subresource" => [
            "method" => "GET",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:list"]
            ]
        ],
        "api_menus_produits_get_subresource" => [
            "method" => "GET",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:list"]
            ]
        ]
    ]
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["burger:detail", "burger:list"])]
    protected $id;

    #[Groups(["burger:detail", "burger:list", "menu:list", "menu:detail"])]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    protected $nom;

    #[Groups(["burger:detail", "burger:list", "menu:list"])]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[ORM\Column(type: 'float')]
    protected $prix;

    #[Groups(["burger:detail", "menu:list"])]
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

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'produits')]
    private $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addProduit($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeProduit($this);
        }

        return $this;
    }
}
