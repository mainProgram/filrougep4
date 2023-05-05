<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\BurgerController;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource(
    operations: [
        new Get(status: 200, normalizationContext: ['groups' => ['produit:detail']]), 
        new Delete(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new Put(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['burger:read']]), 
        new GetCollection(status: 200, security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['produit:read']]), 
        new Post(denormalizationContext: ['groups' => ['produit:write']], normalizationContext: ['groups' => ['burger:read']])])]
#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiFilter(filterClass: NumericFilter::class, properties: ['prix'])]
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
        $this->categorie = "burger";
    }
    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuBurgers() : Collection
    {
        return $this->menuBurgers;
    }
    public function addMenuBurger(MenuBurger $menuBurger) : self
    {
        if (!$this->menuBurgers->contains($menuBurger)) {
            $this->menuBurgers[] = $menuBurger;
            $menuBurger->setBurger($this);
        }
        return $this;
    }
    public function removeMenuBurger(MenuBurger $menuBurger) : self
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
    public function getCommandeBurgers() : Collection
    {
        return $this->commandeBurgers;
    }
    public function addCommandeBurger(CommandeBurger $commandeBurger) : self
    {
        if (!$this->commandeBurgers->contains($commandeBurger)) {
            $this->commandeBurgers[] = $commandeBurger;
            $commandeBurger->setBurger($this);
        }
        return $this;
    }
    public function removeCommandeBurger(CommandeBurger $commandeBurger) : self
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
