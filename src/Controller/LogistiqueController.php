<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LogistiqueController extends AbstractController
{
    /**
     * @Route("/logistique", name="logistique")
     */
    public function index()
    {
        return $this->render('logistique/logistique.html.twig');
    }

    /**
     * @Route("/services", name="services")
     */
    public function services() {
        return $this->render('logistique/services.html.twig');
    }


    /**
     * @Route("/conseils", name="conseils")
     */
    public function conseils() {
        return $this->render('logistique/conseils.html.twig');
    }
}
