<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\MailController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"role", type:"string")]
#[ORM\DiscriminatorMap(["user" => "User", "gestionnaire" => "Gestionnaire", "client" => "Client",  "livreur" => "Livreur"])]
#[ApiResource(
    collectionOperations: [
        "post" => [
            "method" => "post",
            "deserialize"=> false,
            "path" => "/users/validate/{token}",
            "controller" => MailController::class
        ]
    ],
    itemOperations: [
        "get" => [
            "openapi_context" => ["summary"=>"hidden"]
        ]
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups("burger:detail")]
    protected $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(message: "Ce addresse mail est invalide !")]
    #[Groups(["burger:detail", "sign_up:write", "sign_up:read", "livreur:detail"])]
    protected $email;

    #[ORM\Column(type: 'json')]
    protected $roles = [];

    #[Groups(["sign_up:write"])]
    #[Assert\Length(min: 8, minMessage: "Votre mot de passe doit avoir minimum 8 caractères !")]
    #[Assert\Regex(pattern:"/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", message: "Password should contain at least a letter and number and its length > 6 characters !")]
    #[ORM\Column(type: 'string')]
    protected $password;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\EqualTo(propertyPath:"password", message: "Les deux mots de passes se sont pas identiques !")]
    #[Groups(["sign_up:write"])]
    protected $confirmPassword;

    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail", "commande:list"])]
    #[Assert\Length(min:2, minMessage: "Nom invalide !")]
    #[ORM\Column(type: 'string', length: 40)]
    protected $nom;

    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail", "commande:list"])]
    #[Assert\Length(min:2, minMessage: "Prénom invalide !")]
    #[Assert\Regex(pattern:"/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", message: "Nom invalide !")]
    #[ORM\Column(type: 'string', length: 40)]
    protected $prenom;

    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail"])]
    #[Assert\Length(min:9, minMessage: "Longueur invalide !")]
    #[Assert\Regex(pattern:"/^(77|78|75|76|70|33)[0-9]{7}$/", message: "Numéro non valide !")]
    #[ORM\Column(type: 'string', length: 30)]
    protected $telephone;

    #[Groups(["livreur:detail"])]
    #[ORM\Column(type: 'boolean')]
    protected $isEtat = true;

    // #[ApiSubresource()]
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    protected $produits;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected $token;

    #[Groups(["livreur:detail"])]
    #[ORM\Column(type: 'boolean', nullable: true)] //Compte bloqué par défaut
    protected $isActivated = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected $expiredAt;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $table = get_called_class();
        $table = explode("\\", $table);
        $table = strtoupper($table[2]);
        $this->roles[] = "ROLE_".$table;

        // ------------------------token mail
        $this->generateToken();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_VISITEUR';
        // $roles[] = $this->getRole();

        return array_unique($roles);
    }

    // abstract public function getRole();

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(?bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTimeInterface $expiredAt): self
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getDiscr() : string{
        return (new \ReflectionClass($this))->getShortName();
    }

    public function generateToken(){
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
}
