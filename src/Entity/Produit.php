<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 * @UniqueEntity(fields="nom", message="Nom déjà utilisé")
 * @UniqueEntity(fields="reference", message="Référence déjà utilisé")
 * @UniqueEntity(fields="Gencod", message="Gencod déjà utilisé")
 * @Vich\Uploadable
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
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="produits", fileNameProperty="filename")
     */
    private $imageFile;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Gencod;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prix_base;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="array", nullable=true)
     */
    private $feature = [
        'Conditionnement' => '',
        'Unite_par_carton' => '',
        'NB_carton_palette' => '',
        'DLV_garantie'=>'',
        'DLV_Theorique' =>'',
        'Unite_par_couche' => '',
        'Produits_bio' => '',
        'Produits_nouveaux'=> '',
        'Produits_belle_france'=> ''
    ];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $upc;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", mappedBy="id_produit")
     */
    private $id_categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="produits")
     */
    private $id_manufacturer;


    public function __construct()
    {
        $this->id_categorie = new ArrayCollection();
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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

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

    public function getPrixFinal(): ?string
    {
        return $this->prix_final;
    }

    public function setPrixFinal(string $prix_final): self
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

    /**
     * @return null|string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     * @return Produit
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param null|File $imageFile
     * @return Produit
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
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

    public function getFeature(): ?array
    {
        return $this->feature;
    }

    public function setFeature(?array $feature): self
    {
        $this->feature = $feature;

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

    /**
     * @return Collection|Categorie[]
     */
    public function getIdCategorie(): Collection
    {
        return $this->id_categorie;
    }

    public function addIdCategorie(Categorie $idCategorie): self
    {
        if (!$this->id_categorie->contains($idCategorie)) {
            $this->id_categorie[] = $idCategorie;
            $idCategorie->addIdProduit($this);
        }

        return $this;
    }

    public function removeIdCategorie(Categorie $idCategorie): self
    {
        if ($this->id_categorie->contains($idCategorie)) {
            $this->id_categorie->removeElement($idCategorie);
            $idCategorie->removeIdProduit($this);
        }

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
     * @param mixed $id_categorie
     */
    public function setIdCategorie($id_categorie)
    {
        $this->id_categorie = $id_categorie;
    }





}
