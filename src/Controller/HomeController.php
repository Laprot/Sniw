<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {

        if (isset($_COOKIE["site_cookie_check"])){
            $cookie = "";
        }
        else{
            $cookie = setcookie("site_cookie_check", "web-site.com", time() + 365*24*3600, "/", null, false, true);
        }

        return $this->render('home/accueil.html.twig', [
            'cookie' => $cookie
        ]);
    }
}
