<?php

namespace App\Entity;

use App\Repository\CommandeMenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeMenuRepository::class)]
class CommandeMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    private $quantite = 1;

    #[Assert\NotNull(message: "Renseigner un menu !")]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'commandeMenus')]
    private $menu;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'commandeMenus')]
    private $commande;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $prix;

    #[Assert\Valid()]
    #[ORM\OneToMany(mappedBy: 'commandeMenu', targetEntity: CommandeMenuTailleBoisson::class)]
    private $commandeMenuTailleBoissons;

    public function __construct()
    {
        $this->commandeMenuTailleBoissons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, CommandeMenuTailleBoisson>
     */
    public function getCommandeMenuTailleBoissons(): Collection
    {
        return $this->commandeMenuTailleBoissons;
    }

    public function addCommandeMenuTailleBoisson(CommandeMenuTailleBoisson $commandeMenuTailleBoisson): self
    {
        if (!$this->commandeMenuTailleBoissons->contains($commandeMenuTailleBoisson)) {
            $this->commandeMenuTailleBoissons[] = $commandeMenuTailleBoisson;
            $commandeMenuTailleBoisson->setCommandeMenu($this);
        }

        return $this;
    }

    public function removeCommandeMenuTailleBoisson(CommandeMenuTailleBoisson $commandeMenuTailleBoisson): self
    {
        if ($this->commandeMenuTailleBoissons->removeElement($commandeMenuTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenuTailleBoisson->getCommandeMenu() === $this) {
                $commandeMenuTailleBoisson->setCommandeMenu(null);
            }
        }

        return $this;
    }
}
