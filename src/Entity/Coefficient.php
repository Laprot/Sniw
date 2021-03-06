<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoefficientRepository")
 */
class Coefficient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $new_coeff;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groupe", inversedBy="coefficients")
     */
    private $groupes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", inversedBy="coefficients")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
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

    /**
     * @return mixed
     */
    public function getNewCoeff()
    {
        return $this->new_coeff;
    }

    /**
     * @param mixed $new_coeff
     */
    public function setNewCoeff($new_coeff)
    {
        $this->new_coeff = $new_coeff;
    }

    /**
     * @return mixed
     */
    public function getGroupes()
    {
        return $this->groupes;
    }

    /**
     * @param mixed $groupes
     */
    public function setGroupes($groupes)
    {
        $this->groupes = $groupes;
    }





    public function __toString()
    {
        return $this->getNewCoeff();
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addCoefficient($this);
        }
        return $this;
    }
    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeCoefficient($this);
        }
        return $this;
    }
}
