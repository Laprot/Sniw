<?php

namespace App\Controller;


use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\CommandeTypeProduits;
use App\Entity\NouvelleAdresse;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\Search;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;



class PanierController extends AbstractController
{


    private $securityChecker;
    private $token;

    /**
     * @var ProduitRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(ProduitRepository $repository, ObjectManager $em,AuthorizationCheckerInterface $securityChecker, TokenStorageInterface $token)
    {

        $this->repository = $repository;
        $this->em = $em;
        $this->securityChecker = $securityChecker;
        $this->token = $token;
    }


    public function menu(Request $request) {
        $session = $request->getSession();

        if(!$session->has('panier')) {
            $articles = 0;
        }
        else {
            $articles = count($session->get('panier')) ;
        }
        $panier = $session->get('panier');

        if (!empty($panier)) {
            $produits = $this->getDoctrine()->getRepository(Produit::class)->findArray(array_keys($session->get('panier')));
        }
        else {
            $produits = 0;
        }


        return $this->render('panier/affichage.html.twig', [
            'articles' => $articles,
            'panier' => $session->get('panier'),
            'produits' =>$produits
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
     * @Route("/ajouter/ajax/{id}", name="ajouter_ajax")
     */
    public function ajouterActionAjax($id, Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $session = $request->getSession();
            if (!$session->has('panier')) {
                $session->set('panier', []);
            }
            $panier = $session->get('panier');

            if (array_key_exists($id, $panier)) {
                if ($request->request->getInt('quantite') != null) {
                    $panier[$id] += $request->request->getInt('quantite') ;
                }
                else {
                    $panier[$id] += 1 ;
                }
            } else {
                if ($request->request->getInt('quantite') != null) {
                    $panier[$id] = $request->request->getInt('quantite');
                } else {
                    $panier[$id] = 1 ;
                }
            }
            $session->set('panier', $panier);

            return new JsonResponse(['data' => 'this is a json response']);
        }

        return new Response('this is not ajax', 400);
    }


    /**
     * @Route("/recommander/ajax/{id}", name="recommander_ajax")
     */
    public function recommanderActionAjax($id, Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $session = $request->getSession();

            //$produit = $this->getDoctrine()->getRepository(Produit::class)->findBy(['id'=> $id]);

            if (!$session->has('panier')) {
                $session->set('panier', []);
            }
            $panier = $session->get('panier');

            if (array_key_exists($id, $panier)) {
                if ($request->request->getInt('quantite') != null) {
                    $panier[$id] = $request->request->getInt('quantite') ;

                }
            } else {
                if ($request->request->getInt('quantite') != null) {
                    $panier[$id] = $request->request->getInt('quantite');

                } else {
                    $panier[$id] = 1  ;
                }
            }
            $session->set('panier', $panier);

            return new JsonResponse(['data' => 'this is a json response']);
        }

        return new Response('this is not ajax', 400);
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
     * @Route("/supprimer/ajax/{id}", name="supprimer_ajax")
     */
    public function supprimerAjax($id,Request $request) {

        if($request->isXmlHttpRequest()) {
            $session = $request->getSession();

            $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                unset($panier[$id]);
                $session->set('panier', $panier);
            }
            return new JsonResponse(['data' => 'this is a json response']);
        }

        return new Response('this is not ajax', 400);

    }


    //Recommande une commande passée par l'utilisateur
    /**
     * @Route("/recommander/{id}", name="recommander_panier")
     */
    public function recommander(Commande $commande, Request $request)
    {
        foreach ($commande->getCommande()['produit'] as $com) {
            $id = $com['id'];
            $quantite = $com['quantite'];

            $session = $request->getSession();
            if (!$session->has('panier')) {
                $session->set('panier', []);
            }

            $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                }
            } else {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                } else {
                    $panier[$id] = $quantite;
                }
            }
            $session->set('panier',$panier);
        }


        return $this->redirect($this->generateUrl('panier'));
    }


    //Pour recommander une commande importée par l'admin (peu utile)
    /**
     * @Route("/recommander/commandeImport/{id}", name="recommander_commandeimport_panier")
     */
    public function recommanderCommandeImport(Commande $commande,Request $request)
    {
        foreach ($commande->getProduits() as $produit) {
            $id = $produit->getId();

            foreach($produit->getQteProduitCommandes() as $p) {
                if($commande->getId() == $p->getCommande()->getId()) {
                    $quantite = $p->getQuantite();
                }
            }

            $session = $request->getSession();
            if (!$session->has('panier')) {
                $session->set('panier', []);
            }

            $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                }
            } else {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                } else {
                    $panier[$id] = $quantite;
                }
            }
            $session->set('panier',$panier);
        }
        return $this->redirect($this->generateUrl('panier'));
    }



    //Pour recommander une commande type proposée par l'admin

    /**
     * @Route("/recommander/commandeType/{id}", name="recommander_commandeType_panier")
     */
    public function recommanderCommandeType(CommandeTypeProduits $commande,Request $request)
    {
        foreach ($commande->getCommande()->getProduits() as $produit) {
            $id = $produit->getId();

            foreach($produit->getQteProduitCommandes() as $p) {
                if($commande->getCommande()->getId() == $p->getCommande()->getId()) {
                    $quantite = $p->getQuantite();
                }
            }

            $session = $request->getSession();
            if (!$session->has('panier')) {
                $session->set('panier', []);
            }

            $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                }
            } else {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                } else {
                    $panier[$id] = $quantite;
                }
            }
            $session->set('panier',$panier);
        }
        return $this->redirect($this->generateUrl('panier'));
    }



    //Enregistrer le panier

    public function panier(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        //génère un token
        $generator = random_bytes(20);
        $panier = $session->get('panier');
        $commande = [];
        $totalHT = 0;
        $produits = $em->getRepository(Produit::class)->findArray(array_keys($session->get('panier')));

        //On rentre le prix et recapitulatif des produits achetés dans le tableau commande
        foreach($produits as $produit) {
            $prixHT = ($produit->getPrixFinal() * $panier[$produit->getId()]);
            $totalHT += $prixHT;

            $commande['produit'][$produit->getId()] = [
                'reference' => $produit->getReference(),
                'nom' => $produit->getNom(),
                'prixUnitaire' => $produit->getPrixFinal(),
                'quantite' => $panier[$produit->getId()],
                'prixHT' => round($produit->getPrixFinal(), 2),
                'image' => $produit->getImage(),
                'imageImport' => $produit->getImageImport(),
                'id' => $produit->getId(),
                'categories' =>$produit->getCategories()->toArray(),
                'volume' => $produit->getProfondeur(),
                'poids' => $produit->getWeight()
            ];

        }

        $commande['nom'] = $session->get('nom');
        $commande['prixHT'] = round($totalHT, 2);
        $commande['token'] = bin2hex($generator);

        return $commande;
    }


    public function savePanier(Request $request){
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $commande = new Panier();


        $commande->setDate(new \DateTime('now'));


        //On récupère l'utilisateur
        $user = $this->token->getToken()->getUser();

        //Informations clients adresse

        $commande->setPrenom($user->getPrenom());
        $commande->setNom($user->getNom());
        $commande->setUtilisateur($user);


        // Référence aléatoire de 8 lettres
        $characts = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code_aleatoire = '';
        for($i=0;$i<8;$i++){
            $code_aleatoire .= $characts[ rand() % strlen($characts) ];
            $commande->setReference($code_aleatoire);
        }

        //On ajoute le tableau commande via la fonction facture() situé au dessus
        $commande->setCommande($this->panier($request));

        $em->persist($commande);
        $session->set('commande',$commande);

        $em->flush();

        $this->get('session')->getFlashBag()->add('success','Votre panier a bien été enregistrée.');

        return new Response($commande->getId());

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



    /**
     * @Route("/panier/save", name="save_panier")
     */
    public function save(Request $request) {
        //on prépare la commande
        $savePanier = $this->savePanier($request);
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Panier::class)->find($savePanier->getContent());


        $user = $this->token->getToken()->getUser();

        return $this->redirectToRoute('panier_view', [
            'id' => $user->getId()
        ]);
    }


    /**
     * @Route("/panier/{id}/paniers", name="panier_view")
     */
    public function gestionPanier(PaginatorInterface $paginator,User $user,Request $request){
        $this->denyAccessUnlessGranted(AppAccess::USER_EDIT, $user);

        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        $commandes = $paginator->paginate(
            $this->getDoctrine()->getRepository(Panier::class)->findBy(['nom' => $user->getNom()],['id' => 'DESC']),
            $request->query->getInt('page', 1), 10
        );

        $categories =0;
        foreach ($commandes as $commande) {
            if($commande->getCommande() != null)
                foreach ($commande->getCommande()['produit'] as $categorie) {

                    $c = $categorie['categories'][0]->getNom();

                    $categories = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nom' => $c]);

                }
        }

        return $this->render('panier/historique.html.twig', [
            'user' => $user,
            'commandes' => $commandes,
            'form' => $form->createView(),
            'count' => $commandes->getTotalItemCount(),
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/panier/{id}/panier/detail", name="panier_details")
     */
    public function detailssavePanier(Panier $commande) {

        $user = $this->token->getToken()->getUser();


        $this->denyAccessUnlessGranted(AppAccess::PANIER_EDIT, $commande);
        $categories = 0;

        if($commande->getCommande() != null) {
            foreach ($commande->getCommande()['produit'] as $categorie) {
                $c = $categorie['categories'][0]->getNom();
                $categories = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nom' => $c ]);

            }
        }
        return $this->render('panier/savepanier_details.html.twig', [
            'user'=>$user,
            'commande'=>$commande,
            'categories' => $categories
        ]);
    }

    //Recommande une commande passée par l'utilisateur
    /**
     * @Route("/recommander_paniersave/{id}", name="recommander_savepanier")
     */
    public function recommandersavePanier(Panier $commande, Request $request)
    {
        foreach ($commande->getCommande()['produit'] as $com) {
            $id = $com['id'];
            $quantite = $com['quantite'];

            $session = $request->getSession();
            if (!$session->has('panier')) {
                $session->set('panier', []);
            }

            $panier = $session->get('panier');
            if (array_key_exists($id, $panier)) {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                }
            } else {
                if ($request->query->getInt('quantite') != null) {
                    $panier[$id] = $quantite;
                } else {
                    $panier[$id] = $quantite;
                }
            }
            $session->set('panier',$panier);
        }


        return $this->redirect($this->generateUrl('panier'));
    }

    /**
     * @Route("/panier/{id}/panier/delete", name="panier_delete")
     */
    public function deletePanier(Request $request, Panier $commande)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();
            $this->addFlash('success', 'Votre panier enregistré a bien été supprimée.');

            return $this->redirectToRoute('panier_view', [
                'id' => $this->getUser()->getId()
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