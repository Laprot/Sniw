<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Upload
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName( $name)
    {
        $this->name = $name;

        return $this;
    }
}
