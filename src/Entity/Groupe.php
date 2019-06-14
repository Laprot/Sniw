<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroupeRepository")
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="id_groupe")
     */
    private $id_client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Coefficient", mappedBy="groupes")
     */
    private $coefficients;



    public function __construct()
    {
        $this->id_client = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getIdClient(): Collection
    {
        return $this->id_client;
    }

    public function addIdClient(User $idClient): self
    {
        if (!$this->id_client->contains($idClient)) {
            $this->id_client[] = $idClient;
        }
        return $this;
    }

    public function removeIdClient(User $idClient): self
    {
        if ($this->id_client->contains($idClient)) {
            $this->id_client->removeElement($idClient);
        }

        return $this;
    }

    public function __toString()
    {
        $s = ' '.$this->nom;
        return $s;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $id_client
     */
    public function setIdClient($id_client = null)
    {
        $this->id_client = $id_client;
    }

    /**
     * @return mixed
     */
    public function getCoefficients()
    {
        return $this->coefficients;
    }

    /**
     * @param mixed $coefficients
     */
    public function setCoefficients($coefficients)
    {
        $this->coefficients = $coefficients;
    }





}
