<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class Upload
{

    /**
     * @var UploadedFile file
     */
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
