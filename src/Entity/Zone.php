<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\Role\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
#[ApiResource(
   security: 'is_granted("ROLE_GESTIONNAIRE")',
   securityMessage: "Vous n'êtes pas autorisé !",
   collectionOperations: [
    "get" => [
        "normalization_context" => [ "groups" => [ "zone:read"]]
    ],
    "post" => [
        "denormalization_context" => [ "groups" => ["zone:write"]],
        "normalization_context" => [ "groups" => [ "zone:read"]]
    ]],
    itemOperations: [ 
        "get", "put", "delete"
    ]
)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["zone:read"])]
    private $id;

    #[Assert\Length(min:1, minMessage: "Nom invalide !")]
    #[ORM\Column(type: 'string', length: 20, unique: true)]
    #[Groups(["zone:read", "zone:write"])]
    private $nom;

    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    #[Groups(["zone:read", "zone:write", "commande:client:detail"])]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isEtat = true;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Quartier::class)]
    #[Groups(["zone:read", "zone:write"])]
    private $quartiers;

    #[ORM\OneToMany(mappedBy: 'zone', targetEntity: Commande::class)]
    private $commandes;

    public function __construct()
    {
        $this->quartiers = new ArrayCollection();
        $this->commandes = new ArrayCollection();
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

    public function setIsEtat(?bool $isEtat): self
    {
        $this->isEtat = $isEtat;

        return $this;
    }

    /**
     * @return Collection<int, Quartier>
     */
    public function getQuartiers(): Collection
    {
        return $this->quartiers;
    }

    public function addQuartier(Quartier $quartier): self
    {
        if (!$this->quartiers->contains($quartier)) {
            $this->quartiers[] = $quartier;
            $quartier->setZone($this);
        }

        return $this;
    }

    public function removeQuartier(Quartier $quartier): self
    {
        if ($this->quartiers->removeElement($quartier)) {
            // set the owning side to null (unless already changed)
            if ($quartier->getZone() === $this) {
                $quartier->setZone(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setZone($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getZone() === $this) {
                $commande->setZone(null);
            }
        }

        return $this;
    }
}
