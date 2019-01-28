<?php

namespace App\Controller;

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
    public function index(PaginatorInterface $paginator, Request $request)
    {
        //$produits = $this->repository->findAll();


        $produits = $paginator->paginate(
            $this->repository->findAllVisibleQuery(),
            $request->query->getInt('page', 1), 12
        );


        return $this->render('catalogue/catalogue.html.twig',[
            'produits' => $produits
            ]
        );
    }
}
