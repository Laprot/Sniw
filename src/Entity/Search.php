<?php
/**
 * Created by PhpStorm.
 * User: laprot
 * Date: 14/02/2019
 * Time: 09:06
 */

namespace App\Entity;


class Search
{
    private $rechercher;

    /**
     * @return mixed
     */
    public function getRechercher()
    {
        return $this->rechercher;
    }

    /**
     * @param mixed $rechercher
     */
    public function setRechercher($rechercher)
    {
        $this->rechercher = $rechercher;
    }

}