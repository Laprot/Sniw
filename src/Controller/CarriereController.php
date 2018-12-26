<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CarriereController extends AbstractController
{
    /**
     * @Route("/carriere", name="carriere")
     */
    public function index()
    {
        return $this->render('carriere/carriere.html.twig');
    }
}
