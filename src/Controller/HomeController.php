<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route({
     *     "fr": "/fr/",
     *     "en": "/"
     * }, name="home")
     */
    public function index(Request $request)
    {
/*
        if (isset($_COOKIE["site_cookie_check"])){
            $cookie = "";
        }
        else{
            $cookie = setcookie("site_cookie_check", "web-site.com", time() + 365*24*3600, "/", null, false, true);
        }
*/
        return $this->render('home/accueil.html.twig', [
         //   'cookie' => $cookie
        ]);
    }


    /**
     * @Route("/error404", name="error404")
     */
    /*
    public function error404(){
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }

    */
    /**
     * @Route("/error403", name="error403")
     */
    /*
    public function error403(){
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
    }
    */



}
