<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeTypeProduitsRepository")
 * @UniqueEntity(fields="commande", message="Commande déjà utilisé")
 */
class CommandeTypeProduits
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produit", mappedBy="commandeTypeProduits")
     */
    private $produits;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Commande")
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SuperficieMagasin", inversedBy="commandeTypeProduits")
     */
    private $superficie;


    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->commandes = new ArrayCollection();
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

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCommandeTypeProduits($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getCommandeTypeProduits() === $this) {
                $produit->setCommandeTypeProduits(null);
            }
        }

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

    public function getSuperficie(): ?SuperficieMagasin
    {
        return $this->superficie;
    }

    public function setSuperficie(?SuperficieMagasin $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }


}
