<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieProduitRepository::class)]
class CategorieProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'nomCat', targetEntity: Produit::class)]
    //    #[ORM\OneToMany(mappedBy: 'nomCat', targetEntity: Produit::class, cascade:"delete")]

    private Collection $idCategorie;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "The description field cannot be empty")]
    #[Assert\Length(
        min: 3,
        max: 6,
        minMessage: "The description field must be at least {{ limit }} characters long",
        maxMessage: "The description field cannot be longer than {{ limit }} characters"
    )]
    
    private ?string $descriptionCat = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "this field cannot be empty")]
    #[Assert\Length( 
        min : 3, max : 50,
	    minMessage : "The name field must be at least {{ limit }} characters long",
	    maxMessage : "The name field cannot be longer than {{ limit }} characters")]
    private ?string $nomCat = null;


    public function __construct()
    {
        $this->idCategorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getIdCategorie(): Collection
    {
        return $this->idCategorie;
    }

    public function addIdCategorie(Produit $idCategorie): self
    {
        if (!$this->idCategorie->contains($idCategorie)) {
            $this->idCategorie->add($idCategorie);
            $idCategorie->setNomCat($this);
        }

        return $this;
    }

    public function removeIdCategorie(Produit $idCategorie): self
    {
        if ($this->idCategorie->removeElement($idCategorie)) {
            // set the owning side to null (unless already changed)
            if ($idCategorie->getNomCat() === $this) {
                $idCategorie->setNomCat(null);
            }
        }

        return $this;
    }

    public function getDescriptionCat(): ?string
    {
        return $this->descriptionCat;
    }

    public function setDescriptionCat(?string $descriptionCat): self
    {
        $this->descriptionCat = $descriptionCat;

        return $this;
    }

    public function getnomCat(): ?string
    {
        return $this->nomCat;
    }

    public function setnomCat(?string $nomCat): self
    {
        $this->nomCat = $nomCat;

        return $this;
    }

}
