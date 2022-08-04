<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FriteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FriteRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get" => [
            "method" => "get",
            "status" => 200,
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [
                "groups" => ["produit:read"]
            ]
        ],
        "post" => [
            "status" => 201,
            "method" => "post",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => ["groups" => ["produit:detail"]],
            "denormalization_context" => [ "groups" => ["produit:write"]],
        ],
    ],
    itemOperations: [
        "get" => [
            "method" => "get",
            "status" => 200,
            "normalization_context" => [
                "groups" => ["produit:detail"]
            ]
        ],
        "delete" => [
            "method" => "delete",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ],
        "put" => [
            "method" => "put",
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ]
    ]
)]
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
    public function getMenuFrites(): Collection
    {
        return $this->menuFrites;
    }

    public function addMenuFrite(MenuFrite $menuFrite): self
    {
        if (!$this->menuFrites->contains($menuFrite)) {
            $this->menuFrites[] = $menuFrite;
            $menuFrite->setFrite($this);
        }

        return $this;
    }

    public function removeMenuFrite(MenuFrite $menuFrite): self
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
    public function getCommandeFrites(): Collection
    {
        return $this->commandeFrites;
    }

    public function addCommandeFrite(CommandeFrite $commandeFrite): self
    {
        if (!$this->commandeFrites->contains($commandeFrite)) {
            $this->commandeFrites[] = $commandeFrite;
            $commandeFrite->setFrite($this);
        }

        return $this;
    }

    public function removeCommandeFrite(CommandeFrite $commandeFrite): self
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
