<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    public const STATUS_INIT = "Commande Initialisée";
    public const STATUS_ANNULER = "Commande Annulée";
    public const STAUTS_CONFIRMER = "Commande Confirmée";
    public const STAUTUS_EXPEDIER = "Commande Expediée";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation;

    #[ORM\Column(length: 255)]
    private ?string $status = self::STATUS_INIT;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: DetailCommande::class, orphanRemoval: true)]
    private Collection $detailCommandes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payment_method = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payment_brand = null;

    #[ORM\Column(nullable: true)]
    private ?int $cc_last = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payment_status = null;

    public function __construct()
    {
        $this->date_creation = new \DateTimeImmutable();
        $this->detailCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, DetailCommande>
     */
    public function getDetailCommandes(): Collection
    {
        return $this->detailCommandes;
    }

    public function addDetailCommande(DetailCommande $detailCommande): self
    {
        if (!$this->detailCommandes->contains($detailCommande)) {
            $this->detailCommandes->add($detailCommande);
            $detailCommande->setCommande($this);
        }

        return $this;
    }

    public function removeDetailCommande(DetailCommande $detailCommande): self
    {
        if ($this->detailCommandes->removeElement($detailCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailCommande->getCommande() === $this) {
                $detailCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?string $payment_method): self
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getPaymentBrand(): ?string
    {
        return $this->payment_brand;
    }

    public function setPaymentBrand(?string $payment_brand): self
    {
        $this->payment_brand = $payment_brand;

        return $this;
    }

    public function getCcLast(): ?int
    {
        return $this->cc_last;
    }

    public function setCcLast(?int $cc_last): self
    {
        $this->cc_last = $cc_last;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(?string $payment_status): self
    {
        $this->payment_status = $payment_status;

        return $this;
    }
}
