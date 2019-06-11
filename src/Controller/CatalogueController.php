<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Filtre;
use App\Entity\Produit;
use App\Entity\Search;
use App\Form\FiltreType;
use App\Form\RechercheProduitType;
use App\Form\SearchType;
use App\Repository\ProduitRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Imagine\Exception\Exception;
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
     * @Route("/catalogue/{categorie}", name="catalogue")
     */
    public function index(PaginatorInterface $paginator,Request $request, Categorie $categorie = null)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $limit = 32 ;

        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);

        if ($categorie != null)
            $findProduits = $this->repository->byCategorie($categorie);
        else
            $findProduits = $this->repository->findBy(['etat' => 1]);

        //si on utilise la barre de recherche
       if ($form->isSubmitted() && $form->isValid()) {
           //si on ne tape rien dans la barre de recherche,on affiche tous les produits
           if ($search->getRechercher() == null) {
               $produits = $paginator->paginate($this->repository->findAllAvailable(),
                   $request->query->getInt('page', 1), $limit);
           }
           else {
               //Sinon on affiche les produits recherchés
               $produits = $paginator->paginate($this->repository->findAllVisibleQuery($search),
                   $request->query->getInt('page', 1), $limit);
           }

        }
       //sinon on utilise le filtre des produits catégories
       else {
           $produits = $paginator->paginate($findProduits,
               $request->query->getInt('page', 1), $limit);
       }

        //Filtre par checkboxe bio et produit belle france
        if($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            $produits = $paginator->paginate($this->repository->findAllProduitCheckbox($filtre),
                $request->query->getInt('page', 1), $limit);
        }

        //Catégories
        $categories = $this->em->getRepository(Categorie::class)->findAll();


        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $produits,
            'count' => $produits->getTotalItemCount(),
            'form' => $form->createView(),
            'formFiltre'=>$formFiltre->createView(),
            'categories'=>$categories,
            'search' => $search->getRechercher(),
            'limit' => $limit
        ]);
    }


    //Afficher tous les produits

    /**
     * @Route("/display/allproducts", name="catalogue_voirtout")
     * /

    public function toutvoir(Request $request, Categorie $categorie=null) {
        $produits = $this->repository->findAll();

        $count = $this->repository->countProduits();

        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);

        $categories = $this->em->getRepository(Categorie::class)->findAll();

        return $this->render('catalogue/allproduct.html.twig', [
            'produits' => $produits,
            'categories'=> $categories,
            'count'=>$count,
            'formFiltre'=>$formFiltre->createView(),
        ]);
    }


     * */
    /**
     * @Route("/display_cat/allproducts/{categorie}", name="catalogue_voirtout_cat")
     */
    public function toutvoirparcategorie(Request $request, Categorie $categorie) {

        $findProduits = $this->repository->byCategorie($categorie->getId());

        $produits = $findProduits;

        //$count = $this->repository->countProduitsCategorie();

        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);

        //Filtre par checkboxe bio et produit belle france
        if($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            if ($filtre->getIsBelleFrance() == true || $filtre->getIsBio() == true) {
                $produits = $this->repository->findProduitCheckbox($filtre, $categorie);

            }
            else {
                $produits = $findProduits;
            }
        }

        $categories = $this->em->getRepository(Categorie::class)->findAll();

        return $this->render('catalogue/allproduct_cat.html.twig', [
            'produits' => $produits,
            'categories'=> $categories,
            'categorie' => $categorie,
            'count'=>count($produits),
            'formFiltre'=>$formFiltre->createView(),
        ]);
    }







    /**
     * @Route("/catalogue/sous-cat/{id}", name="catalogue_sous-cat", requirements={"id"="\d+"})
     */
    public function sous_categorie(PaginatorInterface $paginator,Request $request, Categorie $categorie)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $limit = 32;

        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);

        if ($categorie != null)
            $findProduits = $this->repository->byCategorie($categorie->getId());
        else
            $findProduits = $this->repository->findBy(array('etat' => 1));


        //si on utilise la barre de recherche
        if ($form->isSubmitted() && $form->isValid()) {
            //si on ne tape rien dans la barre de recherche,on affiche tous les produits
            if ($search->getRechercher() == null) {
                $produits = $paginator->paginate($this->repository->findAllAvailable(),
                    $request->query->getInt('page', 1), $limit);
            }
            else {
                //Sinon on affiche les produits recherchés
                $produits = $paginator->paginate($this->repository->findAllVisibleQuery($search),
                    $request->query->getInt('page', 1), $limit);
            }

        }
        //sinon on utilise le filtre des produits catégories
        else {
            $produits = $paginator->paginate($findProduits,
                $request->query->getInt('page', 1), $limit);
        }

        //Filtre par checkboxe bio et produit belle france
        if($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            if ($filtre->getIsBelleFrance() == true || $filtre->getIsBio() == true) {
                $produits = $paginator->paginate($this->repository->findProduitCheckbox($filtre, $categorie),
                    $request->query->getInt('page', 1), $limit);

            }
            else {
                $produits = $paginator->paginate($findProduits,
                    $request->query->getInt('page', 1), $limit);
            }
        }

        //Catégories
        $categories = $this->em->getRepository(Categorie::class)->findAll();


        return $this->render('catalogue/catalogue_souscategorie.html.twig', [
            'produits' => $produits,
            'count' => $produits->getTotalItemCount(),
            'form' => $form->createView(),
            'formFiltre' => $formFiltre->createView(),
            'categories'=>$categories,
            'search' => $search->getRechercher(),
            'categorie' =>$categorie,
        ]);

    }




    public function rechercheAction() {
        $form = $this->createForm(SearchType::class);
        return $this->render('partiels/recherche.html.twig',[
                'form' => $form->createView()
            ]
        );
    }

    public function rechercheActionCatalogue() {
        $form = $this->createForm(SearchType::class);
        return $this->render('partiels/recherche_catalogue.html.twig',[
                'form' => $form->createView()
            ]
        );
    }



    /**
     * @Route("/catalogue/recherche", name="recherche")
     */

/*
    public function rechercheTraitementAction(Request $request)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        $produits = $this->repository->findAllVisibleQuery($search);

        dump($produits);
        dump($form->getData());
        die();


        return $this->render('catalogue/catalogue.html.twig',[
                'produits' => $produits,
                'form' => $form->createView(),
            ]
        );

    }*/
}
