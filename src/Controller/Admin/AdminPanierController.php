<?php

namespace App\Controller\Admin;

use App\Controller\CommandeController;
use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\Search;
use App\Entity\User;
use App\Form\SearchType;
use App\Form\UserType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\PhpUnit\TextUI\Command;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminPanierController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(PanierRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/panier/show", name="panier_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        //Pagination avec 10 commandes par page
        $commandes = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 10
        );

        foreach($commandes as $commande){

            if($commande->getCommande() != null)
                foreach ($commande->getCommande()['produit'] as $categorie) {
                    $c = $categorie['categories'][0]->getNom();
                    $categories = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nom' => $c ]);

                }

            $user_commandes = $this->getDoctrine()->getRepository(User::class)->findBy(['nom' => $commande->getNom()]);

            foreach($user_commandes as $user_commande) {
                $id_groupe = $user_commande->getIdGroupe()->getId();
            }
        }



        return $this->render('admin/panier/paniers.html.twig', [
            'commandes' => $commandes,
            'count' => $commandes->getTotalItemCount(),
            'form' => $form->createView(),
            'categories' => $categories,
            'id_groupe' => $id_groupe
        ]);
    }

    /**
     * @Route("/admin/{id}/panier/edit", name="panier_edit", methods="GET|POST")
     */
    public function edit(Request $request, Panier $commande)
    {

        $categories = 0;

        if($commande->getCommande() != null)
            foreach ($commande->getCommande()['produit'] as $categorie) {
                $c = $categorie['categories'][0]->getNom();
                $categories = $this->getDoctrine()->getRepository(Categorie::class)->findBy(['nom' => $c ]);
            }

        $user_commandes = $this->getDoctrine()->getRepository(User::class)->findBy(['nom' => $commande->getNom()]);

        foreach($user_commandes as $user_commande) {
            $id_groupe = $user_commande->getIdGroupe()->getId();
        }

        return $this->render('admin/panier/panier_edit.html.twig', [
            'commande' => $commande,
            'categories' => $categories,
            'id_groupe' => $id_groupe
        ]);
    }

    /**
     * @Route("/admin/{id}/panier/delete", name="panier_delete_dashboard", methods="DELETE")
     */
    public function delete(Request $request, Panier $commande)
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();
        }

        return $this->redirectToRoute('panier_show');
    }
}
