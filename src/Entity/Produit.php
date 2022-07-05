<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use App\Controller\ImageController;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type:"string")]
#[ORM\DiscriminatorMap(["produit" => "Produit", "burger" => "Burger",  "frite" => "Frite",  "boisson" => "Boisson", "menu" => "Menu"])]
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial', "isEtat" => "partial" ])]
#[ApiFilter(NumericFilter::class, properties: ['prix'])]
#[ApiResource(
    collectionOperations: [
        "get",
        "/archives" => [
            "status" => 200,
            "path" => "/archives",
            "method" => "GET"
        ],
        'post' => [
            'validate' => false,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
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

    #[Groups(["burger:detail", "burger:list", "menu:list", "menu:detail", "taille_boisson"])]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    protected $nom;

    #[Groups(["burger:detail", "burger:list", "menu:list"])]
    // #[Assert\NotBlank(message: "Ce champ est requis !")]
    // #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[ORM\Column(type: 'float', nullable: true)]
    protected $prix;

    #[Groups(["burger:detail", "menu:detail"])]
    #[ORM\Column(type: 'boolean')]
    protected $isEtat = 1;

    #[Groups(["burger:detail"])]
    #[ORM\Column(type: 'string', length: 255)]
    protected $detail = "";

    #[Groups(["burger:detail"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    protected $user;

    #[ORM\Column(type: 'blob', nullable: true)]
    protected $image;

    // #[ORM\Column(type: 'object', nullable: true)]
    private $imageWrapper;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: CommandeProduit::class)]
    private $commandeProduits;

 
    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->commandeProduits = new ArrayCollection();
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

    public function getImage()
    {
        // $photo = @stream_get_contents($this->image);
        // @fclose($this->image);
        // return base64_encode($photo);
        // dd(utf8_encode(base64_encode($this->image)));
        return utf8_encode(base64_encode($this->image));
        // return utf8_encode(base64_encode($this->image));
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageWrapper(): ?object
    {
        return $this->imageWrapper;
    }

    public function setImageWrapper(?object $imageWrapper): self
    {
        $this->imageWrapper = $imageWrapper;

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
            $commandeProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getProduit() === $this) {
                $commandeProduit->setProduit(null);
            }
        }

        return $this;
    }

  

    
}
