<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $image;

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image_import;

    public function getImageImport()
    {
        return $this->image_import;
    }
    public function setImageImport($image_import)
    {
        $this->image_import = $image_import;
        return $this;
    }

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $reference;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Gencod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prix_base;

    /**
     * @ORM\Column(type="float", length=255,nullable=true)
     */
    private $prix_final;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profondeur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $minimal_quantity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prix_unite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $short_description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $upc;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="produits")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $id_manufacturer;

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Features", mappedBy="produits")
     */
    private $features=[];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $conditionnement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unite_par_carton;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nb_carton_palette;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dlv_garantie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dlv_theorique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unite_par_couche;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $produit_bio;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $produit_nouveau;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $produit_belle_france;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CommandeTypeProduits", inversedBy="produits")
     */
    private $commandeTypeProduits;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", inversedBy="produits")
     */
    private $categories;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CommandeImport", inversedBy="produits")
     */
    private $commandeImport;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande", mappedBy="produits")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $commande;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QteProduitCommande", mappedBy="produit",cascade={"persist"})
     */
    private $qteProduitCommandes;




    public function __construct()
    {
        $this->features = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->commande = new ArrayCollection();
        $this->qteProduitCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getGencod(): ?string
    {
        return $this->Gencod;
    }

    public function setGencod(string $Gencod): self
    {
        $this->Gencod = $Gencod;

        return $this;
    }



    public function getPrixBase(): ?string
    {
        return $this->prix_base;
    }

    public function setPrixBase(string $prix_base): self
    {
        $this->prix_base = $prix_base;

        return $this;
    }

    public function getPrixFinal(): ?float
    {
        return $this->prix_final;
    }

    public function setPrixFinal(float $prix_final): self
    {
        $this->prix_final = $prix_final;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getProfondeur(): ?string
    {
        return $this->profondeur;
    }

    public function setProfondeur(?string $profondeur): self
    {
        $this->profondeur = $profondeur;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getMinimalQuantity(): ?string
    {
        return $this->minimal_quantity;
    }

    public function setMinimalQuantity(?string $minimal_quantity): self
    {
        $this->minimal_quantity = $minimal_quantity;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): self
    {
        $this->unité = $unite;

        return $this;
    }

    public function getPrixUnite(): ?string
    {
        return $this->prix_unite;
    }

    public function setPrixUnite(?string $prix_unite): self
    {
        $this->prix_unite = $prix_unite;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getUpc(): ?string
    {
        return $this->upc;
    }

    public function setUpc(?string $upc): self
    {
        $this->upc = $upc;

        return $this;
    }




    public function getIdManufacturer(): ?Manufacturer
    {
        return $this->id_manufacturer;
    }

    public function setIdManufacturer(?Manufacturer $id_manufacturer): self
    {
        $this->id_manufacturer = $id_manufacturer;

        return $this;
    }

    /**
     * @return Collection|Features[]
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(Features $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
            $feature->addProduit($this);
        }

        return $this;
    }

    public function removeFeature(Features $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
            $feature->removeProduit($this);
        }

        return $this;
    }

    public function getConditionnement(): ?string
    {
        return $this->conditionnement;
    }

    public function setConditionnement(?string $conditionnement): self
    {
        $this->conditionnement = $conditionnement;

        return $this;
    }

    public function getUniteparCarton(): ?string
    {
        return $this->unite_par_carton;
    }

    public function setUniteparCarton(?string $unite_par_carton): self
    {
        $this->unite_par_carton = $unite_par_carton;

        return $this;
    }

    public function getNbCartonPalette(): ?string
    {
        return $this->nb_carton_palette;
    }

    public function setNbCartonPalette(?string $nb_carton_palette): self
    {
        $this->nb_carton_palette = $nb_carton_palette;

        return $this;
    }

    public function getDlvGarantie(): ?string
    {
        return $this->dlv_garantie;
    }

    public function setDlvGarantie(?string $dlv_garantie): self
    {
        $this->dlv_garantie = $dlv_garantie;

        return $this;
    }

    public function getDlvTheorique(): ?string
    {
        return $this->dlv_theorique;
    }

    public function setDlvTheorique(?string $dlv_theorique): self
    {
        $this->dlv_theorique = $dlv_theorique;

        return $this;
    }

    public function getUniteParCouche(): ?string
    {
        return $this->unite_par_couche;
    }

    public function setUniteParCouche(?string $unite_par_couche): self
    {
        $this->unite_par_couche = $unite_par_couche;

        return $this;
    }

    public function getProduitBio(): ?bool
    {
        return $this->produit_bio;
    }

    public function setProduitBio(?bool $produit_bio): self
    {
        $this->produit_bio = $produit_bio;

        return $this;
    }

    public function getProduitNouveau(): ?bool
    {
        return $this->produit_nouveau;
    }

    public function setProduitNouveau(?bool $produit_nouveau): self
    {
        $this->produit_nouveau = $produit_nouveau;

        return $this;
    }

    public function getProduitBelleFrance(): ?bool
    {
        return $this->produit_belle_france;
    }

    public function setProduitBelleFrance(?bool $produit_belle_france): self
    {
        $this->produit_belle_france = $produit_belle_france;

        return $this;
    }

    public function getCommandeTypeProduits(): ?CommandeTypeProduits
    {
        return $this->commandeTypeProduits;
    }

    public function setCommandeTypeProduits(?CommandeTypeProduits $commandeTypeProduits): self
    {
        $this->commandeTypeProduits = $commandeTypeProduits;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categories->contains($categorie)) {
            $this->categories[] = $categorie;
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }



    public function getCommandeImport(): ?CommandeImport
    {
        return $this->commandeImport;
    }

    public function setCommandeImport(?CommandeImport $commandeImport): self
    {
        $this->commandeImport = $commandeImport;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->addProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commande->contains($commande)) {
            $this->commande->removeElement($commande);
            $commande->removeProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection|QteProduitCommande[]
     */
    public function getQteProduitCommandes(): Collection
    {
        return $this->qteProduitCommandes;
    }

    public function addQteProduitCommande(QteProduitCommande $qteProduitCommande): self
    {
        if (!$this->qteProduitCommandes->contains($qteProduitCommande)) {
            $this->qteProduitCommandes[] = $qteProduitCommande;
            $qteProduitCommande->setProduit($this);
        }

        return $this;
    }

    public function removeQteProduitCommande(QteProduitCommande $qteProduitCommande): self
    {
        if ($this->qteProduitCommandes->contains($qteProduitCommande)) {
            $this->qteProduitCommandes->removeElement($qteProduitCommande);
            // set the owning side to null (unless already changed)
            if ($qteProduitCommande->getProduit() === $this) {
                $qteProduitCommande->setProduit(null);
            }
        }

        return $this;
    }

}
