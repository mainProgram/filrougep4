<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiFilter(SearchFilter::class, properties: ['zone.nom' => 'ipartial' ])]
#[ApiResource(
    collectionOperations:[
        "post" => [
            "method" => "post",
            "status" => 201,
        ],
        "get" => [
            "method" => "get",
            "status" => 200,
            "normalization_context" => [
                "groups" => [
                    "commande:list"
                ]
            ]
        ]
    ]
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[Groups(["commande:list"])]
    private $client;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'commandes')]
    private $gestionnaire;

    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    private $zone;

    #[ORM\OneToOne(targetEntity: Ticket::class, cascade: ['persist', 'remove'])]
    private $ticket;
    
    #[Groups(["commande:list"])]
    #[ORM\Column(type: 'string', length: 30)]
    private $etat = "en attente";
    
    #[Groups(["commande:list"])]
    #[ORM\Column(type: 'boolean', nullable: true)]
    private $paye;
    
    #[Groups(["commande:list"])]
    // #[SerializedName("produits")]
    #[Assert\Valid()]
    #[Assert\Count(min:1, minMessage:"Renseignez un burger ou un menu !")]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeProduit::class, cascade: ["persist"])]
    private $commandeProduits;

    #[Groups(["commande:list"])]
    #[ORM\Column(type: 'float', nullable: true)]
    private $prix;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeTailleBoisson::class, cascade: ["persist"])]
    // #[SerializedName("boissons")]
    #[Assert\Valid()]
    #[Assert\Count(min:1, minMessage:"Renseignez une boisson !")]
    private $commandeTailleBoissons;

    #[ORM\Column(type: 'date', nullable: true)]
    private $date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $numero;

  

    public function __construct()
    {
        $this->date = new \DateTime(); 
        $this->commandeProduits = new ArrayCollection();
        $this->commandeTailleBoissons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(?bool $paye): self
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, CommandeTailleBoisson>
     */
    public function getCommandeTailleBoissons(): Collection
    {
        return $this->commandeTailleBoissons;
    }

    public function addCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson): self
    {
        if (!$this->commandeTailleBoissons->contains($commandeTailleBoisson)) {
            $this->commandeTailleBoissons[] = $commandeTailleBoisson;
            $commandeTailleBoisson->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeTailleBoisson(CommandeTailleBoisson $commandeTailleBoisson): self
    {
        if ($this->commandeTailleBoissons->removeElement($commandeTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($commandeTailleBoisson->getCommande() === $this) {
                $commandeTailleBoisson->setCommande(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

 
}
