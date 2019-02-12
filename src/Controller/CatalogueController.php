<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\RechercheProduitType;
use App\Repository\ProduitRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{


    /**
     * @var ProduitRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProduitRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/catalogue", name="catalogue")
     */
    public function index(Request $request)
    {
        $produits = $this->repository->findAll();

        return $this->render('catalogue/catalogue.html.twig',[
            'produits' => $produits
            ]
        );
    }


    public function rechercheAction() {
        $form = $this->createForm(RechercheProduitType::class);
        return $this->render('partiels/recherche.html.twig',[
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/recherche", name="recherche")
     */
    public function rechercheTraitementAction(Request $request) {


        $form = $this->createForm(RechercheProduitType::class);

        $form->handleRequest($request);


        if($request->getMethod() == 'POST'){
            $produits = $this->repository->recherche($form['recherche']->getData());
        } else {
            throw $this->createNotFoundException('La page n\'existe pas');
        }


        return $this->render('catalogue/catalogue.html.twig',[
                'produits' => $produits
            ]
        );
    }
}
