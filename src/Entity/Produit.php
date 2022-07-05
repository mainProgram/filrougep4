<?php

namespace App\Entity;

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
        "get",
        "/archives" => [
            "status" => 200,
            "path" => "/archives",
            "method" => "GET"
        ],
        // "post" => [
        //     "method" => "post",
        //     "path" => "/addImg",
        //     "controller" => ImageController::class,
        //     "deserialize" => false
        // ]
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
    #[Assert\NotBlank(message: "Ce champ est requis !")]
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

    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'produit')]
    protected $commandeProduits;

    #[ORM\Column(type: 'blob', nullable: true)]
    protected $image;

    // #[ORM\Column(type: 'object', nullable: true)]
    private $imageWrapper;
  
    public function __construct()
    {
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

    /**
    //  * @return Collection<int, Commande>
    //  */
    // public function getCommandeProduits(): Collection
    // {
    //     return $this->commandeProduits;
    // }

    // public function addCommandeProduits(Commande $commande): self
    // {
    //     if (!$this->commandeProduits->contains($commande)) {
    //         $this->commandeProduits[] = $commande;
    //         $commande->addProduit($this);
    //     }

    //     return $this;
    // }

    // public function removeCommandeProduits(Commande $commande): self
    // {
    //     if ($this->commandeProduits->removeElement($commande)) {
    //         $commande->removeProduit($this);
    //     }

    //     return $this;
    // }


    /**
     * Get the value of commandeProduits
     */ 
    public function getCommandeProduits()
    {
        return $this->commandeProduits;
    }

    /**
     * Set the value of commandeProduits
     *
     * @return  self
     */ 
    public function setCommandeProduits($commandeProduits)
    {
        $this->commandeProduits = $commandeProduits;

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

   

}
