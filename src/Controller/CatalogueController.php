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

        if ($form->isSubmitted() && $form->isValid()) {
            $produits = $paginator->paginate($this->repository->findAllVisibleQuery($search),
                $request->query->getInt('page', 1), 20);
        }
        //sinon on utilise le filtre des produits catégories
        else {
            $produits = $paginator->paginate($findProduits,
                $request->query->getInt('page', 1), 20);
        }

        //Pagination avec 10 users par page
        /*$produits = $paginator->paginate(
            $findProduits,
            $request->query->getInt('page', 1), 10
        );*/
        //Catégories
        $categories = $this->em->getRepository(Categorie::class)->findAll();
        return $this->render('catalogue/cataloguetest.html.twig', [
            'produits' => $produits,
            'count' => $produits->getTotalItemCount(),
            'form' => $form->createView(),
            'categories'=>$categories
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
