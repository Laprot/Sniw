<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FiltreRepository")
 */
class Filtre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var ArrayCollection
     */
    private $marque;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBelleFrance;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBio;


    public function __construct()
    {
        $this->marque = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsBelleFrance(): ?bool
    {
        return $this->isBelleFrance;
    }

    public function setIsBelleFrance(?bool $isBelleFrance): self
    {
        $this->isBelleFrance = $isBelleFrance;

        return $this;
    }

    public function getIsBio(): ?bool
    {
        return $this->isBio;
    }

    public function setIsBio(?bool $isBio): self
    {
        $this->isBio = $isBio;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getMarque(): ArrayCollection
    {
        return $this->marque;
    }

    /**
     * @param ArrayCollection $marque
     */
    public function setMarque(ArrayCollection $marque)
    {
        $this->marque = $marque;
    }


}
