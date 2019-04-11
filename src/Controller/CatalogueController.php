<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Search;
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

        if ($categorie != null)
            $findProduits = $this->repository->byCategorie($categorie);
        else
            $findProduits = $this->repository->findBy(array('etat' => 1));

        //si on utilise la barre de recherche
       if ($form->isSubmitted() && $form->isValid()) {
           //si on ne tape rien dans la barre de recherche,on affiche tous les produits
           if ($search->getRechercher() == null) {
               $produits = $paginator->paginate($this->repository->findAllAvailable(),
                   $request->query->getInt('page', 1), 24);
           }
           else {
               //Sinon on affiche les produits recherchés
               $produits = $paginator->paginate($this->repository->findAllVisibleQuery($search),
                   $request->query->getInt('page', 1), 24);
           }

        }
       //sinon on utilise le filtre des produits catégories
       else {
           $produits = $paginator->paginate($findProduits,
               $request->query->getInt('page', 1), 24);
       }


        //Catégories
        $categories = $this->em->getRepository(Categorie::class)->findAll();


        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $produits,
            'count' => $produits->getTotalItemCount(),
            'form' => $form->createView(),
            'categories'=>$categories,
            'search' => $search->getRechercher()
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

        if ($categorie != null)
            $findProduits = $this->repository->byCategorie($categorie);
        else
            $findProduits = $this->repository->findBy(array('etat' => 1));

        //si on utilise la barre de recherche
        if ($form->isSubmitted() && $form->isValid()) {
            //si on ne tape rien dans la barre de recherche,on affiche tous les produits
            if ($search->getRechercher() == null) {
                $produits = $paginator->paginate($this->repository->findAllAvailable(),
                    $request->query->getInt('page', 1), 24);
            }
            else {
                //Sinon on affiche les produits recherchés
                $produits = $paginator->paginate($this->repository->findAllVisibleQuery($search),
                    $request->query->getInt('page', 1), 24);
            }

        }
        //sinon on utilise le filtre des produits catégories
        else {
            $produits = $paginator->paginate($findProduits,
                $request->query->getInt('page', 1), 24);
        }


        if($categorie->getNom() == '-') {
            $categorie->setNom($categorie->getIdParent()->getNom());
        }


        //Catégories
        $categories = $this->em->getRepository(Categorie::class)->findAll();


        return $this->render('catalogue/catalogue_souscategorie.html.twig', [
            'produits' => $produits,
            'count' => $produits->getTotalItemCount(),
            'form' => $form->createView(),
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
