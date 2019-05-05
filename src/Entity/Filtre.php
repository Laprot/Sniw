<?php

namespace App\Entity;

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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBelleFrance;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBio;

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
}
