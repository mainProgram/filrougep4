<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use App\Services\CommandeService;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['commande:client:detail']]), 
        new Put(normalizationContext: ['groups' => ['commande:list']]), 
        new Post(status: 201, normalizationContext: ['groups' => ['commande:read']]), 
        new GetCollection(paginationItemsPerPage: 10, status: 200, normalizationContext: ['groups' => ['commande:list']])
    ]
)]
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[Assert\Callback([CommandeService::class, "isThereABurgerOrAMenu"])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['date'])]
#[ApiResource(
    uriTemplate: '/clients/{id}/commandes.{_format}', 
    uriVariables: ['id' => new Link(fromClass: \App\Entity\Client::class, identifiers: ['id'])], 
    status: 200, 
    filters: ['annotated_app_entity_commande_api_platform_core_bridge_doctrine_orm_filter_search_filter', 'annotated_app_entity_commande_api_platform_core_bridge_doctrine_orm_filter_exists_filter', 'annotated_app_entity_commande_api_platform_core_bridge_doctrine_orm_filter_date_filter'], 
    operations: [new GetCollection()]
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(["commande:client:read", "commande:list", "livraison:detail", "commande:client:detail"])]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[Groups(["commande:list", "livraison:detail"])]
    #[Assert\NotNull(message: "Renseignez le client !")]
    private $client;
    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;
    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'commandes')]
    private $gestionnaire;
    #[Groups(["commande:client:detail", "commande:list", "livraison:detail"])]
    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    private $zone;
    #[ORM\OneToOne(targetEntity: Ticket::class, cascade: ['persist', 'remove'])]
    private $ticket;
    #[Groups(["commande:list", "commande:client:read", "commande:client:detail", "livraison:detail"])]
    #[ORM\Column(type: 'string', length: 30)]
    private $etat = "en attente";
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $paye;
    #[Groups(["commande:client:read", "commande:client:detail", "commande:list", "livraison:detail"])]
    #[ORM\Column(type: 'float', nullable: true)]
    private $prix;
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeTailleBoisson::class, cascade: ["persist"])]
    #[Groups(["commande:client:detail"])]
    #[Assert\Valid]
    private $commandeTailleBoissons;
    #[Groups(["commande:client:read", "commande:list"])]
    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date;
    #[Groups(["commande:list", "livraison:detail"])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $numero;
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMenu::class, cascade: ["persist"])]
    #[Groups(["commande:client:detail"])]
    private $commandeMenus;
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeFrite::class, cascade: ["persist"])]
    #[Groups(["commande:client:detail"])]
    private $commandeFrites;
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeBurger::class, cascade: ["persist"])]
    #[Groups(["commande:client:detail"])]
    private $commandeBurgers;
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->commandeTailleBoissons = new ArrayCollection();
        $this->commandeMenus = new ArrayCollection();
        $this->commandeFrites = new ArrayCollection();
        $this->commandeBurgers = new ArrayCollection();
    }
    public function getId() : ?int
    {
        return $this->id;
    }
    public function getClient() : ?Client
    {
        return $this->client;
    }
    public function setClient(?Client $client) : self
    {
        $this->client = $client;
        return $this;
    }
    public function getLivraison() : ?Livraison
    {
        return $this->livraison;
    }
    public function setLivraison(?Livraison $livraison) : self
    {
        $this->livraison = $livraison;
        return $this;
    }
    public function getGestionnaire() : ?Gestionnaire
    {
        return $this->gestionnaire;
    }
    public function setGestionnaire(?Gestionnaire $gestionnaire) : self
    {
        $this->gestionnaire = $gestionnaire;
        return $this;
    }
    public function getZone() : ?Zone
    {
        return $this->zone;
    }
    public function setZone(?Zone $zone) : self
    {
        $this->zone = $zone;
        return $this;
    }
    public function getTicket() : ?Ticket
    {
        return $this->ticket;
    }
    public function setTicket(?Ticket $ticket) : self
    {
        $this->ticket = $ticket;
        return $this;
    }
    public function getEtat() : ?string
    {
        return $this->etat;
    }
    public function setEtat(string $etat) : self
    {
        $this->etat = $etat;
        return $this;
    }
    public function isPaye() : ?bool
    {
        return $this->paye;
    }
    public function setPaye(?bool $paye) : self
    {
        $this->paye = $paye;
        return $this;
    }
    public function getPrix() : ?float
    {
        return $this->prix;
    }
    public function setPrix(?float $prix) : self
    {
        $this->prix = $prix;
        return $this;
    }
    /**
     * @return Collection<int, CommandeTailleBoisson>
     */
    public function getCommandeTailleBoissons() : Collection
    {
        return $this->commandeTailleBoissons;
    }
    public function addCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson) : self
    {
        if (!$this->commandeTailleBoissons->contains($commandeTailleBoisson)) {
            $this->commandeTailleBoissons[] = $commandeTailleBoisson;
            $commandeTailleBoisson->setCommande($this);
        }
        return $this;
    }
    public function removeCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson) : self
    {
        if ($this->commandeTailleBoissons->removeElement($commandeTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($commandeTailleBoisson->getCommande() === $this) {
                $commandeTailleBoisson->setCommande(null);
            }
        }
        return $this;
    }
    public function getDate() : ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(?\DateTimeInterface $date) : self
    {
        $this->date = $date;
        return $this;
    }
    public function getNumero() : ?string
    {
        return $this->numero;
    }
    public function setNumero(?string $numero) : self
    {
        $this->numero = $numero;
        return $this;
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
            $commandeMenu->setCommande($this);
        }
        return $this;
    }
    public function removeCommandeMenu(CommandeMenu $commandeMenu) : self
    {
        if ($this->commandeMenus->removeElement($commandeMenu)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenu->getCommande() === $this) {
                $commandeMenu->setCommande(null);
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
            $commandeFrite->setCommande($this);
        }
        return $this;
    }
    public function removeCommandeFrite(CommandeFrite $commandeFrite) : self
    {
        if ($this->commandeFrites->removeElement($commandeFrite)) {
            // set the owning side to null (unless already changed)
            if ($commandeFrite->getCommande() === $this) {
                $commandeFrite->setCommande(null);
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
            $commandeBurger->setCommande($this);
        }
        return $this;
    }
    public function removeCommandeBurger(CommandeBurger $commandeBurger) : self
    {
        if ($this->commandeBurgers->removeElement($commandeBurger)) {
            // set the owning side to null (unless already changed)
            if ($commandeBurger->getCommande() === $this) {
                $commandeBurger->setCommande(null);
            }
        }
        return $this;
    }
}
