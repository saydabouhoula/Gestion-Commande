<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $categ = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $model = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $tauxRemise = null;


    #[ORM\Column(nullable: true)]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'idCategorie')]
    private ?CategorieProduit $nomCat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img2 = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: DetailCommande::class, orphanRemoval: true)]
    private Collection $detailCommandes;

    public function __construct()
    {
        $this->detailCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescr(): ?string
    {
        return $this->descr;
    }

    public function setDescr(?string $descr): self
    {
        $this->descr = $descr;

        return $this;
    }

    public function getCateg(): ?string
    {
        return $this->categ;
    }

    public function setCateg(?string $categ): self
    {
        $this->categ = $categ;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }


    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setimg(?string $img): self
    {
        $this->img = $img;

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


    public function gettauxRemise(): ?int
    {
        return $this->tauxRemise;
    }

    public function settauxRemise(?int $tauxRemise): self
    {
        $this->tauxRemise = $tauxRemise;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getNomCat(): ?CategorieProduit
    {
        return $this->nomCat;
    }

    public function setNomCat(?CategorieProduit $nomCat): self
    {
        $this->nomCat = $nomCat;

        return $this;
    }

    public function getImg2(): ?string
    {
        return $this->img2;
    }

    public function setImg2(?string $img2): self
    {
        $this->img2 = $img2;

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
            $detailCommande->setProduit($this);
        }

        return $this;
    }

    public function removeDetailCommande(DetailCommande $detailCommande): self
    {
        if ($this->detailCommandes->removeElement($detailCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailCommande->getProduit() === $this) {
                $detailCommande->setProduit(null);
            }
        }

        return $this;
    }
}
