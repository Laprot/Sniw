<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReductionRepository")
 */
class Reduction
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
    private $new_reduction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groupe", inversedBy="reductions")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="reductions")
     */
    private $categories;

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
    public function getNewReduction()
    {
        return $this->new_reduction;
    }

    /**
     * @param mixed $new_reduction
     */
    public function setNewReduction($new_reduction)
    {
        $this->new_reduction = $new_reduction;
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

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }



    public function __toString()
    {
        return $this->getNewReduction();
    }
}
