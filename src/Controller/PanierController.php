<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use App\Security\AppAccess;
use Doctrine\Common\Persistence\ObjectManager;
use Imagine\Exception\Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
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
      * @Route("/ajouter/{id}", name="ajouter")
      */
    public function ajouterAction($id, Request $request) {
        $session = $request->getSession();

        if(!$session->has('panier')) {
            $session->set('panier', []);
        }
        $panier = $session->get('panier');

        if(array_key_exists($id,$panier)) {
            if($request->query->get('quantite') != null) {
                $panier[$id] = $request->query->get('quantite');
            }
        }
        else {
            if($request->query->get('quantite') != null) {
                $panier[$id] = $request->query->get('quantite');
            }
            else {
                $panier[$id] = 1 ;
            }
        }

        $session->set('panier',$panier);

        return $this->redirect($this->generateUrl('panier'));
    }



    /**
     * @Route("/panier", name="panier")
     */
    public function index(Request $request)
    {
        $session = $request->getSession();

        //$session->remove('panier');
        //die();
        if(!$session->has('panier')) {
            $session->set('panier', []);
        }


        $produits = $this->repository->findArray(array_keys($session->get('panier')));


        return $this->render('panier/recapitulatif.html.twig', [
            'produits' => $produits,
            'panier' => $session->get('panier')
        ]);

    }



    public function livraison(){
        return $this->render('panier/livraison.html.twig');
    }


    public function supprimer($id) {

    }

    public function confirmation() {
        return $this->render('panier/confirmation.html.twig');
    }


}