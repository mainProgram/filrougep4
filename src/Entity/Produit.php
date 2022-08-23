<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\ImageController;
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
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial', "isEtat" => "exact" ])]
#[ApiFilter(NumericFilter::class, properties: ['prix'])]
#[ApiResource(
    collectionOperations: [
        "get",
        "/archives" => [
            "status" => 200,
            "path" => "/archives",
            "method" => "GET",
            "normalization_context" => ["groups" => ["produit:detail",]]
        ],
        // 'post' => [
        //     'validate' => false,
        //     'input_formats' => [
        //         'multipart' => ['multipart/form-data'],
        //     ],
        // ],
    ],
    subresourceOperations: [
        "api_users_produits_get_subresource" => [
            "method" => "GET",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:read"]
            ]
        ],
        "api_menus_produits_get_subresource" => [
            "method" => "GET",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["burger:read"]
            ]
        ]
    ],
    itemOperations:[
        "get" => [
            "normalization_context" => ["groups" => ["produit:detail",]]
        ],
        "put"
    ] 
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["produit:detail", "burger:read", "burger:read", "boisson:read", "produit:read"])]
    protected $id;

    #[Groups(["produit:detail","burger:read", "produit:read","produit:write", "menu:detail","complement:read", "boisson:write", "commande:list", "boisson:write", "commande:client:detail", "boisson:read"])]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    protected $nom;

    #[Groups(["produit:detail", "produit:read", "commande:client:detail", "burger:read"])]
    #[ORM\Column(type: 'float', nullable: true)]
    protected $prix;

    #[Groups(["produit:detail", "menu:detail"])]
    #[ORM\Column(type: 'boolean')]
    protected $isEtat = 1;

    #[Groups(["produit:detail", "produit:write"])]
    #[ORM\Column(type: 'string', length: 255)]
    protected $detail = "";

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    protected $user;

    #[ORM\Column(type: 'blob', nullable: true)]
    #[Groups(["produit:read",  "produit:write", "complement:read", "boisson:write", "commande:client:detail", "produit:detail"])]
    protected $image;

    #[Groups(["produit:write"])]
    private $imageWrapper;

    #[Groups(["produit:read", "complement:read", "produit:detail", "produit:write"])]
    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $categorie;
 
    public function __construct()
    {
        $this->commande = new ArrayCollection();
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
        return is_resource($this->image) ? utf8_encode(base64_encode(stream_get_contents($this->image))) : $this->image;

        // return ($this->image);
        // return utf8_encode(base64_encode($this->image));
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

   


    /**
     * Get the value of imageWrapper
     */ 
    public function getImageWrapper()
    {
        return $this->imageWrapper;
    }

    /**
     * Set the value of imageWrapper
     *
     * @return  self
     */ 
    public function setImageWrapper($imageWrapper)
    {
        $this->imageWrapper = $imageWrapper;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
