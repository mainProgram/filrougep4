<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommandeMenuTailleBoissonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeMenuTailleBoissonRepository::class)]
class CommandeMenuTailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[Assert\NotBlank(message: "Ce champ est requis !")]
    #[Assert\Positive(message: "La quantité doit être supérieure à 0 !")]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $quantite = 1;

    #[ORM\ManyToOne(targetEntity: CommandeMenu::class, inversedBy: 'commandeMenuTailleBoissons', cascade: ["persist"])]
    private $commandeMenu;

    #[Assert\NotNull(message: "Renseigner une boisson!")]
    #[ORM\ManyToOne(targetEntity: TailleBoisson::class, inversedBy: 'commandeMenuTailleBoissons')]
    private $tailleBoisson;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCommandeMenu(): ?CommandeMenu
    {
        return $this->commandeMenu;
    }

    public function setCommandeMenu(?CommandeMenu $commandeMenu): self
    {
        $this->commandeMenu = $commandeMenu;

        return $this;
    }

    public function getTailleBoisson(): ?TailleBoisson
    {
        return $this->tailleBoisson;
    }

    public function setTailleBoisson(?TailleBoisson $tailleBoisson): self
    {
        $this->tailleBoisson = $tailleBoisson;

        return $this;
    }
}
