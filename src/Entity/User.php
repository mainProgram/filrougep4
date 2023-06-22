<?php

namespace App\Entity;

use App\Entity\Produit;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\MailController;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
#[ApiResource(
    operations: [
        new Get(), 
        new Put(), 
        // new Get(openapiContext: ['summary' => 'hidden']), 
        new GetCollection(deserialize: false, uriTemplate: '/users/validate/{token}', controller: MailController::class), 
        new GetCollection(normalizationContext: ['groups' => ['user:read']])
    ]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "role", type: "string")]
#[ORM\DiscriminatorMap(["user" => "User", "gestionnaire" => "Gestionnaire", "client" => "Client", "livreur" => "Livreur"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["burger:detail", "sign_up:read", "livreur:read", "livreur:detail", "livraison:detail", "user:read"])]
    protected $id;
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(message: "Cette adresse mail est invalide !")]
    #[Groups(["burger:detail", "sign_up:write", "sign_up:read", "livreur:detail", "user:read"])]
    protected $email;
    #[ORM\Column(type: 'json')]
    protected $roles = [];
    #[Groups(["sign_up:write"])]
    #[ORM\Column(type: 'string')]
    protected $password;
    // #[Assert\NotBlank(message: "Ce champ est requis !")]
    // #[Assert\EqualTo(propertyPath:"password", message: "Les deux mots de passes se sont pas identiques !")]
    #[Groups(["sign_up:write"])]
    protected $confirmPassword;
    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail", "commande:list", "livraison:read", "livraison:detail", "livreur:read"])]
    #[ORM\Column(type: 'string', length: 40)]
    protected $nom;
    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail", "commande:list", "livraison:read", "livraison:detail", "livreur:read"])]
    #[ORM\Column(type: 'string', length: 40)]
    protected $prenom;
    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail", "livreur:read", "livraison:detail"])]
    #[ORM\Column(type: 'string', length: 30)]
    protected $telephone;
    #[ORM\Column(type: 'boolean')]
    protected $isEtat = true;
    // #[ApiSubresource()]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    protected $produits;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected $token;
    #[ORM\Column(type: 'boolean', nullable: true)]
    protected $isActivated = false;
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $expiredAt;
    #[Groups(["sign_up:write", "sign_up:read"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse;
    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $table = get_called_class();
        $table = explode("\\", $table);
        $table = strtoupper($table[2]);
        $this->roles[] = "ROLE_" . $table;
        // ------------------------token mail
        $this->generateToken();
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getEmail() : ?string
    {
        return $this->email;
    }
    public function setEmail(string $email) : self
    {
        $this->email = $email;
        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier() : string
    {
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */
    public function getRoles() : array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_VISITEUR';
        // $roles[] = $this->getRole();
        return array_unique($roles);
    }
    // abstract public function getRole();
    public function setRoles(array $roles) : self
    {
        $this->roles = $roles;
        return $this;
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword() : string
    {
        return $this->password;
    }
    public function setPassword(string $password) : self
    {
        $this->password = $password;
        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
    public function getPrenom() : ?string
    {
        return $this->prenom;
    }
    public function setPrenom(string $prenom) : self
    {
        $this->prenom = $prenom;
        return $this;
    }
    public function getTelephone() : ?string
    {
        return $this->telephone;
    }
    public function setTelephone(string $telephone) : self
    {
        $this->telephone = $telephone;
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
    /**
     * @return Collection<int, Produit>
     */
    public function getProduits() : Collection
    {
        return $this->produits;
    }
    public function addProduit(Produit $produit) : self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setUser($this);
        }
        return $this;
    }
    public function removeProduit(Produit $produit) : self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }
        return $this;
    }
    public function getToken() : ?string
    {
        return $this->token;
    }
    public function setToken(?string $token) : self
    {
        $this->token = $token;
        return $this;
    }
    public function isIsActivated() : ?bool
    {
        return $this->isActivated;
    }
    public function setIsActivated(?bool $isActivated) : self
    {
        $this->isActivated = $isActivated;
        return $this;
    }
    public function getExpiredAt() : ?\DateTimeInterface
    {
        return $this->expiredAt;
    }
    public function setExpiredAt(?\DateTimeInterface $expiredAt) : self
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }
    public function getDiscr() : string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
    public function generateToken()
    {
        $this->expiredAt = new \Datetime("+1 day");
        $this->token = bin2hex(openssl_random_pseudo_bytes(16));
    }
    /**
     * Get the value of confirmPassword
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }
    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }
    public function getAdresse() : ?string
    {
        return $this->adresse;
    }
    public function setAdresse(?string $adresse) : self
    {
        $this->adresse = $adresse;
        return $this;
    }
}
