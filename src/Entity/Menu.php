<?php

namespace App\Entity;

use App\Entity\Frite;
use App\Entity\Burger;
use App\Entity\Taille;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\MenuController;
use App\Repository\MenuRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [ "groups" => ["produit:list"]   ]
        ],
        "post" => [
            "method" => "post",
            "status" => 201,
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "denormalization_context" => [   "groups" => ["menu:write"]],   
        ],
        // "addMenu" => [
        //     "method" => "post",
        //     "deserialize" => false,
        //     "status" => 201,
        //     "security" => "is_granted('ROLE_GESTIONNAIRE')",
        //     "security_message" => "Vous n'êtes pas autorisé !",
        //     "controller" => MenuController::class,
        //     "path" => "/addMenu",
            // "denormalization_context" => [
            //     "groups" => ["menu:write"]
            // ],  
        // ]
    ],
    itemOperations:[
        "put" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
          
        ],
        "delete" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
        ],
        "get" => [
            "normalization_context" => [
                "groups" => ["produit:detail"]
            ]
        ]
    ]
)]
#[ApiFilter(PropertyFilter::class)]
class Menu extends Produit
{

    #[Groups(["menu:write"])]
    // #[Assert\NotBlank(message: "Ce champ est requis !")]
    protected $nom;

    // #[Groups(["menu:write"])]
    // #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[SerializedName("image")]
    protected $imageWrapper;

    #[Assert\Valid()]
    #[Groups(["menu:write"])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuFrite::class, cascade: ["persist"])]
    private $menuFrites;

    #[Groups(["menu:write"])]
    #[Assert\Valid()]
    #[Assert\Count(min:1, minMessage: "Renseignez le burger !")]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class, cascade: ["persist"])]
    private $menuBurgers;

    #[Assert\Valid()]
    #[Groups(["menu:write"])]
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
    }
  
  
    /**
     * @return Collection<int, MenuFrite>
     */
    public function getMenufrites(): Collection
    {
        return $this->menuFrites;
    }

    public function addMenufrite(MenuFrite $menufrite): self
    {
        if (!$this->menuFrites->contains($menufrite)) {
            $this->menuFrites[] = $menufrite;
            $menufrite->setMenu($this);
        }

        return $this;
    }

    public function removeMenufrite(MenuFrite $menufrite): self
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
    public function getMenuburgers(): Collection
    {
        return $this->menuBurgers;
    }

    public function addMenuburger(MenuBurger $menuburger): self
    {
        if (!$this->menuBurgers->contains($menuburger)) {
            $this->menuBurgers[] = $menuburger;
            $menuburger->setMenu($this);
        }

        return $this;
    }

    public function removeMenuburger(MenuBurger $menuburger): self
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
    public function getMenuTailles(): Collection
    {
        return $this->menuTailles;
    }

    public function addMenuTaille(MenuTaille $menuTaille): self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setMenu($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
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
    public function getCommandeMenus(): Collection
    {
        return $this->commandeMenus;
    }

    public function addCommandeMenu(CommandeMenu $commandeMenu): self
    {
        if (!$this->commandeMenus->contains($commandeMenu)) {
            $this->commandeMenus[] = $commandeMenu;
            $commandeMenu->setMenu($this);
        }

        return $this;
    }

    public function removeCommandeMenu(CommandeMenu $commandeMenu): self
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
