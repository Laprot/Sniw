<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
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
    private $reference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $livraison;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $societe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paiement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $poids;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLivraison(): ?string
    {
        return $this->livraison;
    }

    public function setLivraison(?string $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getPaiement(): ?string
    {
        return $this->paiement;
    }

    public function setPaiement(?string $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

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

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(?int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }
}
