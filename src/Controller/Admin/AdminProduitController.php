<?php

namespace App\Controller\Admin;


use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Search;
use App\Form\ProduitCategorieType;
use App\Form\ProduitImageType;
use App\Form\ProduitType;
use App\Form\SearchType;
use App\Repository\ProduitRepository;

use App\Service\FileUploader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminProduitController extends AbstractController
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
     * @Route("/admin/client/produits/show", name="produits_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);


        //Pagination avec 10 produits par page
        $produits = $paginator->paginate(
            $this->repository->findAllVisibleQueryAdmin($search),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('admin/produits/data-produits.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
            'count' => $produits->getTotalItemCount()
        ]);
    }


    /**
     * @Route("/admin/produits/new", name="produits_new")
     */
    public function new(Request $request, FileUploader $fileUploader)
    {
        // 1) build the form
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $produit->getImage();
            $fileName = $fileUploader->upload($file);
            $produit->setImage($fileName);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('produits_show');
        }

        return $this->render(
            'admin/produits/new_produits.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/{id}/produit/edit", name="produits_edit", methods="GET|POST")
     */
    public function edit(Request $request, Produit $produit,FileUploader $fileUploader)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();


        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produits_show', [
                'id' => $produit->getId()
            ]);
        }
        return $this->render('admin/produits/edit_produits.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'categorie'=>$categorie
        ]);
    }

    /**
     * @Route("/admin/{id}/produit/edit/image", name="produits_edit_image", methods="GET|POST")
     */
    public function editImage(Request $request, Produit $produit,FileUploader $fileUploader)
    {
        $form = $this->createForm(ProduitImageType::class, $produit);
        $form->handleRequest($request);

        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();


        if ($form->isSubmitted() && $form->isValid()) {

           if((sizeof($produit->getImage()) > 0 ) && sizeof($produit->getImageImport() > 0)) {
               $file = $produit->getImage();
               $fileName = $fileUploader->upload($file);
               $produit->setImage($fileName);
               $produit->setImageImport(null);
           }
            else {
                $file = $produit->getImage();
                $fileName = $fileUploader->upload($file);
                $produit->setImage($fileName);
                $produit->setImageImport(null);
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produits_show', [
                'id' => $produit->getId()
            ]);
        }
        return $this->render('admin/produits/edit_image_produits.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'categorie'=>$categorie
        ]);
    }


    /**
     * @Route("/admin/{id}/produit/delete", name="produits_delete", methods="DELETE")
     */
    public function delete(Request $request, Produit $produit)
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produits_show');
    }
}