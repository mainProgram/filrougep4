<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            "normalization_context" => [
                "groups" => ["menu:list"]
            ]
        ],
        "post" => [
            "method" => "post",
            "status" => 201,
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'êtes pas autorisé !",
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
            // "denormalization_context" => [
            //     "groups" => ["menu:write"]
            // ]
        ]
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
                "groups" => ["menu:detail", ]
            ]
        ],
        "patch"

    ]
)]
#[ApiFilter(PropertyFilter::class)]
class Menu extends Produit
{
    
    #[ORM\ManyToMany(targetEntity: Taille::class, inversedBy: 'menus')]
    private $tailles;

    #[Groups(["menu:write"])]
    protected $nom;

    // #[Groups(["menu:write"])]
    protected $image;

    #[Groups(["menu:write"])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuFrite::class, cascade: ["persist"])]
    private $menufrites;

    #[Groups(["menu:write"])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class, cascade: ["persist"])]
    private $menuburgers;

    #[Groups(["menu:write"])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuTailleBoisson::class, cascade: ["persist"])]
    private $menuTailleBoissons;

   

    public function __construct()
    {
        parent::__construct();
        $this->tailles = new ArrayCollection();
        $this->menufrites = new ArrayCollection();
        $this->menuburgers = new ArrayCollection();
        $this->menuTailleBoissons = new ArrayCollection();
    }
    

    // /**
    //  * @return Collection<int, TailleBoisson>
    //  */
    // public function getTailleBoissons(): Collection
    // {
    //     return $this->tailleBoissons;
    // }

    // public function addTailleBoisson(TailleBoisson $tailleBoisson): self
    // {
    //     if (!$this->tailleBoissons->contains($tailleBoisson)) {
    //         $this->tailleBoissons[] = $tailleBoisson;
    //     }

    //     return $this;
    // }

    // public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    // {
    //     $this->tailleBoissons->removeElement($tailleBoisson);

    //     return $this;
    // }

    /**
     * @return Collection<int, Taille>
     */
    public function getTailles(): Collection
    {
        return $this->tailles;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->tailles->contains($taille)) {
            $this->tailles[] = $taille;
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        $this->tailles->removeElement($taille);

        return $this;
    }
  
    /**
     * @return Collection<int, MenuFrite>
     */
    public function getMenufrites(): Collection
    {
        return $this->menufrites;
    }

    public function addMenufrite(MenuFrite $menufrite): self
    {
        if (!$this->menufrites->contains($menufrite)) {
            $this->menufrites[] = $menufrite;
            $menufrite->setMenu($this);
        }

        return $this;
    }

    public function removeMenufrite(MenuFrite $menufrite): self
    {
        if ($this->menufrites->removeElement($menufrite)) {
            // set the owning side to null (unless already changed)
            if ($menufrite->getMenu() === $this) {
                $menufrite->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuburgers(): Collection
    {
        return $this->menuburgers;
    }

    public function addMenuburger(MenuBurger $menuburger): self
    {
        if (!$this->menuburgers->contains($menuburger)) {
            $this->menuburgers[] = $menuburger;
            $menuburger->setMenu($this);
        }

        return $this;
    }

    public function removeMenuburger(MenuBurger $menuburger): self
    {
        if ($this->menuburgers->removeElement($menuburger)) {
            // set the owning side to null (unless already changed)
            if ($menuburger->getMenu() === $this) {
                $menuburger->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuTailleBoisson>
     */
    public function getMenuTailleBoissons(): Collection
    {
        return $this->menuTailleBoissons;
    }

    public function addMenuTailleBoisson(MenuTailleBoisson $menuTailleBoisson): self
    {
        if (!$this->menuTailleBoissons->contains($menuTailleBoisson)) {
            $this->menuTailleBoissons[] = $menuTailleBoisson;
            $menuTailleBoisson->setMenu($this);
        }

        return $this;
    }

    public function removeMenuTailleBoisson(MenuTailleBoisson $menuTailleBoisson): self
    {
        if ($this->menuTailleBoissons->removeElement($menuTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($menuTailleBoisson->getMenu() === $this) {
                $menuTailleBoisson->setMenu(null);
            }
        }

        return $this;
    }

  

   

   
}
