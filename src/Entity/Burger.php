<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\BurgerController;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
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
            "denormalization_context" => [ "groups" => ["produit:write"]],
            "security_post_denormalize" => "is_granted('BURGER_CREATE', object)",
            "security_post_denormalize_message" => "Access dentzbgnukjm" 
        ],
        // "post" => [
        //     "status" => 201,
        //     "method" => "post",
        //     "security" => "is_granted('ROLE_GESTIONNAIRE')",
        //     "security_message" => "Vous n'êtes pas autorisé !",
        //     "normalization_context" => [
        //         "groups" => ["burger:detail"]
        //     ],
        //     // "deserialize" => false
        // ],
        // "addBurger" => [
        //     "status" => 201,
        //     "method" => "post",
        //     "security" => "is_granted('ROLE_GESTIONNAIRE')",
        //     "security_message" => "Vous n'êtes pas autorisé !",
        //     "path" => "/addBurger",
        //     "controller" => BurgerController::class
        // ]
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
#[ApiFilter(SearchFilter::class, properties: ['nom' => 'ipartial' ])]
#[ApiFilter(NumericFilter::class, properties: ['prix'])]
class Burger extends Produit
{
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Groups(["produit:write"])]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0 !")]
    protected $prix;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: MenuBurger::class)]
    private $menuBurgers;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: CommandeBurger::class)]
    private $commandeBurgers;

    public function __construct()
    {
        parent::__construct();
        $this->menuBurgers = new ArrayCollection();
        $this->commandeBurgers = new ArrayCollection();
    }

    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuBurgers(): Collection
    {
        return $this->menuBurgers;
    }

    public function addMenuBurger(MenuBurger $menuBurger): self
    {
        if (!$this->menuBurgers->contains($menuBurger)) {
            $this->menuBurgers[] = $menuBurger;
            $menuBurger->setBurger($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getBurger() === $this) {
                $menuBurger->setBurger(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeBurger>
     */
    public function getCommandeBurgers(): Collection
    {
        return $this->commandeBurgers;
    }

    public function addCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if (!$this->commandeBurgers->contains($commandeBurger)) {
            $this->commandeBurgers[] = $commandeBurger;
            $commandeBurger->setBurger($this);
        }

        return $this;
    }

    public function removeCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if ($this->commandeBurgers->removeElement($commandeBurger)) {
            // set the owning side to null (unless already changed)
            if ($commandeBurger->getBurger() === $this) {
                $commandeBurger->setBurger(null);
            }
        }

        return $this;
    }
}
