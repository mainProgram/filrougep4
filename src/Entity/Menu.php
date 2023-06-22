<?php

namespace App\Entity;

use App\Entity\Frite;
use App\Entity\Burger;
use App\Entity\Taille;
use App\Entity\Produit;
use App\Entity\MenuFrite;
use App\Entity\MenuBurger;
use App\Entity\MenuTaille;
use App\Entity\CommandeMenu;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\MenuController;
use App\Repository\MenuRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
#[ApiResource(
    operations: [
        new Put(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new Delete(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !'), 
        new Get(normalizationContext: ['groups' => ['produit:detail']]), 
        new GetCollection(security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', normalizationContext: ['groups' => ['produit:list']]), 
        new Post(status: 201, security: 'is_granted(\'ROLE_GESTIONNAIRE\')', securityMessage: 'Vous n\'êtes pas autorisé !', denormalizationContext: ['groups' => ['menu:write']])
    ]
)]
#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiFilter(filterClass: PropertyFilter::class)]
class Menu extends Produit
{
    #[Groups(["menu:write"])]
    protected $nom;
    // #[Groups(["menu:write"])]
    // #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[SerializedName("image")]
    protected $imageWrapper;
    #[Assert\Valid]
    #[SerializedName("frites")]
    #[Groups(["menu:write", "produit:detail"])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuFrite::class, cascade: ["persist"])]
    private $menuFrites;
    #[Groups(["menu:write", "produit:detail"])]
    #[Assert\Valid]
    #[Assert\Count(min: 1, minMessage: "Renseignez le burger !")]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class, cascade: ["persist"])]
    #[SerializedName("burgers")]
    private $menuBurgers;
    #[Assert\Valid]
    #[SerializedName("tailles")]
    #[Groups(["menu:write", "produit:detail"])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuTaille::class, cascade: ["persist"])]
    private $menuTailles;
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: CommandeMenu::class)]
    private $commandeMenus;
    public function __construct()
    {
        parent::__construct();
        $this->menuFrites = new ArrayCollection();
        $this->menuBurgers = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
        $this->commandeMenus = new ArrayCollection();
        $this->categorie = "menu";
    }
    /**
     * @return Collection<int, MenuFrite>
     */
    public function getMenufrites() : Collection
    {
        return $this->menuFrites;
    }
    public function addMenufrite(MenuFrite $menufrite) : self
    {
        if (!$this->menuFrites->contains($menufrite)) {
            $this->menuFrites[] = $menufrite;
            $menufrite->setMenu($this);
        }
        return $this;
    }
    public function removeMenufrite(MenuFrite $menufrite) : self
    {
        if ($this->menuFrites->removeElement($menufrite)) {
            // set the owning side to null (unless already changed)
            if ($menufrite->getMenu() === $this) {
                $menufrite->setMenu(null);
            }
        }
        return $this;
    }
    public function addFrite(Frite $frite, int $quantite)
    {
        $menufrite = new MenuFrite();
        $menufrite->setQuantite($quantite);
        $menufrite->setFrite($frite);
        $menufrite->setMenu($this);
        $this->addMenuFrite($menufrite);
    }
    public function addBurger(Burger $burger, int $quantite)
    {
        $menuBurger = new MenuBurger();
        $menuBurger->setQuantite($quantite);
        $menuBurger->setBurger($burger);
        $menuBurger->setMenu($this);
        $this->addMenuburger($menuBurger);
    }
    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuburgers() : Collection
    {
        return $this->menuBurgers;
    }
    public function addMenuburger(MenuBurger $menuburger) : self
    {
        if (!$this->menuBurgers->contains($menuburger)) {
            $this->menuBurgers[] = $menuburger;
            $menuburger->setMenu($this);
        }
        return $this;
    }
    public function removeMenuburger(MenuBurger $menuburger) : self
    {
        if ($this->menuBurgers->removeElement($menuburger)) {
            // set the owning side to null (unless already changed)
            if ($menuburger->getMenu() === $this) {
                $menuburger->setMenu(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, MenuTaille>
     */
    public function getMenuTailles() : Collection
    {
        return $this->menuTailles;
    }
    public function addMenuTaille(MenuTaille $menuTaille) : self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setMenu($this);
        }
        return $this;
    }
    public function removeMenuTaille(MenuTaille $menuTaille) : self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getMenu() === $this) {
                $menuTaille->setMenu(null);
            }
        }
        return $this;
    }
    public function addTaille(Taille $taille, int $quantite)
    {
        $menutaille = new MenuTaille();
        $menutaille->setQuantite($quantite);
        $menutaille->setTaille($taille);
        $menutaille->setMenu($this);
        $this->addMenuTaille($menutaille);
    }
    /**
     * @return Collection<int, CommandeMenu>
     */
    public function getCommandeMenus() : Collection
    {
        return $this->commandeMenus;
    }
    public function addCommandeMenu(CommandeMenu $commandeMenu) : self
    {
        if (!$this->commandeMenus->contains($commandeMenu)) {
            $this->commandeMenus[] = $commandeMenu;
            $commandeMenu->setMenu($this);
        }
        return $this;
    }
    public function removeCommandeMenu(CommandeMenu $commandeMenu) : self
    {
        if ($this->commandeMenus->removeElement($commandeMenu)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenu->getMenu() === $this) {
                $commandeMenu->setMenu(null);
            }
        }
        return $this;
    }
}
