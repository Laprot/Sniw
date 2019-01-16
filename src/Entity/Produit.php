<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
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
     * @ORM\Column(type="string", length=255)
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




}
