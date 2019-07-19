<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Coefficient;
use App\Entity\Filtre;
use App\Entity\Groupe;
use App\Entity\Manufacturer;
use App\Entity\Produit;
use App\Entity\Search;
use App\Form\FiltreAllMarqueType;
use App\Form\FiltreMarqueType;
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

        if ($categorie != null)
            $findProduits = $this->repository->byCategorie($categorie);
        else
            $findProduits = $this->repository->findBy(['etat' => 1]);


        $produits = $paginator->paginate($this->repository->findAllVisibleQuery($search),
                $request->query->getInt('page', 1), $limit);


        //Récupère les produits du panier
        $session = $request->getSession();
        $panier = $session->get('panier');

        if(!empty($panier)) {
            $produits_panier = $this->getDoctrine()->getRepository(Produit::class)->findArray(array_keys($session->get('panier')));
        }
        else {
            $produits_panier = 0;
        }


        //sinon on utilise le filtre des produits catégories


        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);
        //Filtre par checkboxe bio et produit belle france
        if($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            $produits = $paginator->paginate($this->repository->findAllProduitCheckbox($filtre),
                $request->query->getInt('page', 1), $limit);

        }


        //Catégories
        $categories = $this->em->getRepository(Categorie::class)->findAll();

        //Current user
        $user = $this->getUser();
        return $this->render('catalogue/catalogue.html.twig', [
            'produits' => $produits,
            'count' => $produits->getTotalItemCount(),
            'form' => $form->createView(),
            'formFiltre'=>$formFiltre->createView(),
            'categories'=>$categories,
            'search' => $search->getRechercher(),
            'limit' => $limit,
            'user' => $user,
            'produits_panier' => $produits_panier,
            'panier' => $session->get('panier'),
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
     * @Route("/display_cat/allproducts/{id}", name="catalogue_voirtout_cat")
     */
    public function toutvoirparcategorie(Request $request, Categorie $categorie) {

        $produits = $this->repository->byCategorie($categorie->getId());

        $bf = [];
        $bio = [];
        $both = [];

        foreach($produits as $prod) {
            if ($prod->getProduitBelleFrance() == true) {
                array_push($bf, $prod);
            }
            if($prod->getProduitBio() == true) {
                array_push($bio,$prod);
            }
            if($prod->getProduitBelleFrance() == true && $prod->getProduitBio() == true) {
                array_push($both,$prod);
            }
        }

        /*
        foreach($produits as $prod) {
            array_push($marques,$prod->getIdManufacturer()->getId());
            $marque_prod = array_unique($marques);
        }
        */
        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);

        //Filtre par checkboxe bio et produit belle france
        if($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            if($filtre->getIsBelleFrance() == true) {
                $produits = $bf;
            }
            if($filtre->getIsBio() == true) {
                $produits = $bio;
            }
            if($filtre->getIsBio() == true && $filtre->getIsBelleFrance() == true){
                $produits = $both;
            }
         }

        $filtre_marque= new Filtre();
        $formFiltreMarque = $this->createForm(FiltreMarqueType::class,$filtre_marque,['categorie' => $categorie]);
        $formFiltreMarque->handleRequest($request);
        $marques = [];
        //Filtre par marque
        /*

       if($formFiltreMarque->isSubmitted() && $formFiltreMarque->isValid()) {
            foreach($formFiltreMarque->getData()->getMarque() as $k => $data) {
                foreach ($produits as $prod) {
                    if ($prod->getIdManufacturer()->getId() == $data->getId()) {
                        array_push($marques, $prod);
                        $produits = $marques;
                    }
                }
            }
        }
        */


        //Filtre par marque
        if($formFiltreMarque->isSubmitted() && $formFiltreMarque->isValid()) {
            $produits = $this->repository->findProduitCheckboxDisplayMarque($filtre_marque, $categorie);
        }

        $categories = $this->em->getRepository(Categorie::class)->findAll();

        return $this->render('catalogue/allproduct_cat.html.twig', [
            'produits' => $produits,
            'categories'=> $categories,
            'categorie' => $categorie,
            'count'=>count($produits),
            'formFiltre'=>$formFiltre->createView(),
            'formFiltreMarque' => $formFiltreMarque->createView()
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

        $filtre= new Filtre();
        $formFiltre = $this->createForm(FiltreType::class,$filtre);
        $formFiltre->handleRequest($request);


        //Filtre par checkboxe bio,produit belle france
        if($formFiltre->isSubmitted() && $formFiltre->isValid()) {
            $produits = $paginator->paginate($this->repository->findProduitCheckbox($filtre, $categorie),
                $request->query->getInt('page', 1), $limit);
        }


        $filtre_marque= new Filtre();
        $formFiltreMarque = $this->createForm(FiltreMarqueType::class,$filtre_marque,['categorie' => $categorie]);
        $formFiltreMarque->handleRequest($request);


        //Filtre par marque
        if($formFiltreMarque->isSubmitted() && $formFiltreMarque->isValid()) {
            $produits = $paginator->paginate($this->repository->findProduitCheckboxMarque($filtre_marque, $categorie),
                $request->query->getInt('page', 1), $limit);
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
            'formFiltreMarque' => $formFiltreMarque->createView()
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
