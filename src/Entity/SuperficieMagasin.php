<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SuperficieMagasinRepository")
 */
class SuperficieMagasin
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="superficieMagasin")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommandeTypeProduits", mappedBy="superficie")
     */
    private $commandeTypeProduits;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->commandeTypeProduits = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setSuperficieMagasin($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSuperficieMagasin() === $this) {
                $user->setSuperficieMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommandeTypeProduits[]
     */
    public function getCommandeTypeProduits(): Collection
    {
        return $this->commandeTypeProduits;
    }

    public function addCommandeTypeProduit(CommandeTypeProduits $commandeTypeProduit): self
    {
        if (!$this->commandeTypeProduits->contains($commandeTypeProduit)) {
            $this->commandeTypeProduits[] = $commandeTypeProduit;
            $commandeTypeProduit->setSuperficie($this);
        }

        return $this;
    }

    public function removeCommandeTypeProduit(CommandeTypeProduits $commandeTypeProduit): self
    {
        if ($this->commandeTypeProduits->contains($commandeTypeProduit)) {
            $this->commandeTypeProduits->removeElement($commandeTypeProduit);
            // set the owning side to null (unless already changed)
            if ($commandeTypeProduit->getSuperficie() === $this) {
                $commandeTypeProduit->setSuperficie(null);
            }
        }

        return $this;
    }

}
