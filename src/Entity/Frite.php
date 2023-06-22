<?php

namespace App\Entity;

use App\Entity\Produit;
use App\Entity\MenuFrite;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use App\Entity\CommandeFrite;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\FriteRepository;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(
    operations: [
        new Get(status: 200, normalizationContext: ['groups' => ['produit:detail']]), 
        new Delete(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new Put(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new GetCollection(status: 200, security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['produit:read']]), 
        new Post(status: 201, security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['produit:detail']], denormalizationContext: ['groups' => ['produit:write']])
    ]
)]
#[ORM\Entity(repositoryClass: FriteRepository::class)]
class Frite extends Produit
{
    #[Groups(["complement:read"])]
    protected $id;
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Groups(["produit:write", "complement:read"])]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    protected $prix;
    #[ORM\OneToMany(mappedBy: 'frite', targetEntity: MenuFrite::class)]
    private $menuFrites;
    #[ORM\OneToMany(mappedBy: 'frite', targetEntity: CommandeFrite::class)]
    private $commandeFrites;
    public function __construct()
    {
        parent::__construct();
        $this->menuFrites = new ArrayCollection();
        $this->commandeFrites = new ArrayCollection();
        $this->categorie = "frite";
    }
    /**
     * @return Collection<int, MenuFrite>
     */
    public function getMenuFrites() : Collection
    {
        return $this->menuFrites;
    }
    public function addMenuFrite(MenuFrite $menuFrite) : self
    {
        if (!$this->menuFrites->contains($menuFrite)) {
            $this->menuFrites[] = $menuFrite;
            $menuFrite->setFrite($this);
        }
        return $this;
    }
    public function removeMenuFrite(MenuFrite $menuFrite) : self
    {
        if ($this->menuFrites->removeElement($menuFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuFrite->getFrite() === $this) {
                $menuFrite->setFrite(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, CommandeFrite>
     */
    public function getCommandeFrites() : Collection
    {
        return $this->commandeFrites;
    }
    public function addCommandeFrite(CommandeFrite $commandeFrite) : self
    {
        if (!$this->commandeFrites->contains($commandeFrite)) {
            $this->commandeFrites[] = $commandeFrite;
            $commandeFrite->setFrite($this);
        }
        return $this;
    }
    public function removeCommandeFrite(CommandeFrite $commandeFrite) : self
    {
        if ($this->commandeFrites->removeElement($commandeFrite)) {
            // set the owning side to null (unless already changed)
            if ($commandeFrite->getFrite() === $this) {
                $commandeFrite->setFrite(null);
            }
        }
        return $this;
    }
}
