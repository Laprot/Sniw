<?php

namespace App\Controller\Admin;

use App\Entity\CommandeTypeProduits;
use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\CommandeTypeProduitsRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminCommandeTypeController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(CommandeTypeProduitsRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/commandetype/show", name="commandetype_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        //Pagination avec 10 commandes par page
        $commandesTypes = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('admin/commande/commandestypes.html.twig', [
            'commandesTypes' => $commandesTypes,
            'count' => $commandesTypes->getTotalItemCount(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}/commandetype/edit", name="commandetype_edit", methods="GET|POST")
     */
    public function edit(Request $request, CommandeTypeProduits $commandeType)
    {
        return $this->render('admin/commande/commandestypes_edit.html.twig', [
            'commandeType' => $commandeType
        ]);
    }

    /**
     * @Route("/admin/{id}/commandetype/delete", name="commandetype_delete_dashboard")
     */
    public function delete(CommandeTypeProduits $commandeTypeProduits)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commandeTypeProduits);
        $em->flush();

        return $this->redirectToRoute('commandetype_show');
    }
}
