<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivreurRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['isDisponible' => 'exact'])]
#[ApiResource(
    // security: 'is_granted("ROLE_GESTIONNAIRE)',
    // securityMessage : "Vous n'êtes pas autorisé !",
    collectionOperations:[
        "get" => [
            "normalization_context" => ["groups" => ["sign_up:read"]]
        ],
        "post" => [
            "denormalization_context" => [ "groups" => ["sign_up:write"]],
            "normalization_context" => ["groups" => ["sign_up:read"]]
        ]
        ],
        itemOperations:[
            "get" => [
                "normalization_context" => [ "groups" => ["livreur:detail"]],
            ]
        ]
)]
class Livreur extends User
{
    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail"])]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $matriculeMoto;

    #[ApiSubresource()]
    #[Groups(["livreur:detail"])]
    #[ORM\OneToMany(mappedBy: 'livreur', targetEntity: Livraison::class)]
    private $livraisons;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isDisponible;

    public function __construct()
    {
        parent::__construct();
        $this->livraisons = new ArrayCollection();
    }

    public function getMatriculeMoto(): ?string
    {
        return $this->matriculeMoto;
    }

    public function setMatriculeMoto(string $matriculeMoto): self
    {
        $this->matriculeMoto = $matriculeMoto;

        return $this;
    }

    /**
     * @return Collection<int, Livraison>
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setLivreur($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getLivreur() === $this) {
                $livraison->setLivreur(null);
            }
        }

        return $this;
    }

    public function isIsDisponible(): ?bool
    {
        return $this->isDisponible;
    }

    public function setIsDisponible(?bool $isDisponible): self
    {
        $this->isDisponible = $isDisponible;

        return $this;
    }
}
