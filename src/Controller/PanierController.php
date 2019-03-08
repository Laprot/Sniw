<?php

namespace App\Controller;


use App\Entity\Commande;
use App\Entity\NouvelleAdresse;
use App\Entity\User;
use App\Form\AdresseUserType;
use App\Form\ChoixAdresseType;
use App\Form\NouvelleAdresseType;
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

    public function menu(Request $request) {
        $session = $request->getSession();
        if(!$session->has('panier')) {
            $articles = 0;
        }
        else {
            $articles = count($session->get('panier'));
        }
        return $this->render('panier/affichage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
      * @Route("/ajouter/{id}", name="ajouter")
      */
    public function ajouterAction($id, Request $request)
    {
        $session = $request->getSession();
        if (!$session->has('panier')) {
            $session->set('panier', []);
        }
        $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $request->query->getInt('quantite');
                }
            } else {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $request->query->getInt('quantite');
                } else {
                    $panier[$id] = 1;
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
            'panier' => $session->get('panier'),

        ]);

    }


    /**
     * @Route("/panier/adresse/{id}", name="livraison", methods="GET|POST")
     */
    public function livraison(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);
        $form = $this->createForm(AdresseUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('adresses', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('panier/adresse.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mesAdresses/{id}", name="adresses")
     */
    public function mesAdresses(User $user, Request $request) {

        $adresses = $this->getDoctrine()->getRepository(NouvelleAdresse::class)->findAll();
        return $this->render('panier/current_adresse.html.twig', [
            'user' => $user,
            'adresses' => $adresses
        ]);
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer($id,Request $request) {
        $session = $request->getSession();

        $panier= $session->get('panier');
        if(array_key_exists($id,$panier)) {
            unset($panier[$id]);
            $session->set('panier',$panier);
        }

        return $this->redirect($this->generateUrl('panier'));

    }


    /**
     * @Route("/panier/nouvelle_Adresse/{id}", name="new_adresse", methods="GET|POST")
     */
    public function nouvelleAdresse(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);
        $new = new NouvelleAdresse();

        $form = $this->createForm(NouvelleAdresseType::class, $new);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($new);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('adresses', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('panier/new_adresse.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/panier/transport", name="transport")
     */
    public function transport(){
        return $this->render('panier/transport.html.twig');
    }

    /**
     * @Route("/panier/confirmation/{id}", name="confirmation")
     */
    public function confirmation(Request $request,User $user) {
        $session = $request->getSession();

        //$session->remove('panier');
        //die();
        if(!$session->has('panier')) {
            $session->set('panier', []);
        }


        $produits = $this->repository->findArray(array_keys($session->get('panier')));
        return $this->render('panier/confirmation.html.twig', [
            'produits' => $produits,
            'panier' => $session->get('panier'),
            'user' => $user

        ]);
    }

}