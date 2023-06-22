<?php

namespace App\Entity;

use App\Entity\Livraison;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\LivreurRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['livreur:detail']]), 
        new Put(normalizationContext: ['groups' => ['livreur:detail']]), 
        new GetCollection(normalizationContext: ['groups' => ['livreur:read']]), 
        new Post(denormalizationContext: ['groups' => ['sign_up:write']], normalizationContext: ['groups' => ['sign_up:read']])
    ]
)]
#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['isDisponible' => 'exact'])]
class Livreur extends User
{
    #[Groups(["sign_up:write", "sign_up:read", "livreur:detail", "livreur:read"])]
    #[ORM\Column(type: 'string', length: 50, unique: true)]
    private $matriculeMoto;
    // #[ApiSubresource]
    #[Groups(["livreur:detail"])]
    #[ORM\OneToMany(mappedBy: 'livreur', targetEntity: Livraison::class)]
    private $livraisons;
    #[Groups(["livreur:read", "livreur:detail"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isDisponible;
    public function __construct()
    {
        parent::__construct();
        $this->livraisons = new ArrayCollection();
    }
    public function getMatriculeMoto() : ?string
    {
        return $this->matriculeMoto;
    }
    public function setMatriculeMoto(string $matriculeMoto) : self
    {
        $this->matriculeMoto = $matriculeMoto;
        return $this;
    }
    /**
     * @return Collection<int, Livraison>
     */
    public function getLivraisons() : Collection
    {
        return $this->livraisons;
    }
    public function addLivraison(Livraison $livraison) : self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setLivreur($this);
        }
        return $this;
    }
    public function removeLivraison(Livraison $livraison) : self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getLivreur() === $this) {
                $livraison->setLivreur(null);
            }
        }
        return $this;
    }
    public function isIsDisponible() : ?bool
    {
        return $this->isDisponible;
    }
    public function setIsDisponible(?bool $isDisponible) : self
    {
        $this->isDisponible = $isDisponible;
        return $this;
    }
}
