<?php

namespace App\Controller\Admin;

use App\Controller\CommandeController;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Search;
use App\Entity\User;
use App\Form\SearchType;
use App\Form\UserType;
use App\Repository\CommandeRepository;
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

class AdminCommandeImportController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(CommandeRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/commande_import/show", name="commande_import_show")
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

        return $this->render('admin/commande/commandes_import.html.twig', [
            'commandes' => $commandes,
            'count' => $commandes->getTotalItemCount(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}/commande_import/edit", name="commande_import_edit", methods="GET|POST")
     */
    public function edit(Request $request, Commande $commande)
    {
        return $this->render('admin/commande/commande_import_edit.html.twig', [
            'commande' => $commande
        ]);
    }

    /**
     * @Route("/admin/{id}/commande_import/delete", name="commande_import_delete_dashboard", methods="DELETE")
     */
    public function delete(Request $request, Commande $commande)
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();
        }

        return $this->redirectToRoute('commande_import_show');
    }


    /**
     * @Route("/admin/commande_import/produit/supprimer/{id}", name="supprimer_produit_commande")
     */
    public function supprimerProduit(Commande $commande,Request $request) {


        return $this->redirectToRoute('commande_edit', [
            'commande' => $commande->getId()
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

}
