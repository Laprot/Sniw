<?php


namespace App\Controller\Admin;

use App\Entity\Groupe;
use App\Entity\Search;
use App\Entity\User;
use App\Form\GroupeType;
use App\Form\SearchType;
use App\Form\UserType;
use App\Repository\GroupeRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminGroupeController extends AbstractController
{

    /**
     * @var GroupeRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(GroupeRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/groupe/show", name="groupe_show")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        // Récupère tous les groupes
        //$users = $this->repository->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);

        //Pagination avec 10 groupes par page
        $groupes = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('admin/groupe/groupes.html.twig', [
            'groupes' => $groupes,
            'form' => $form->createView(),
            'count' => $groupes->getTotalItemCount(),
        ]);
    }




    /**
     * @Route("/admin/groupe/{id}/edit", name="groupe_edit", methods="GET|POST")
     */
    public function edit(Request $request, Groupe $groupe,PaginatorInterface $paginator)
    {
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groupe_show', [
                'id' => $groupe->getId()
            ]);
        }

        return $this->render('admin/groupe/groupe_edit.html.twig', [
            'groupe' => $groupe,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/groupe/new", name="groupe_new")
     */
    public function register(Request $request)
    {
        // 1) build the form
        $groupe = new Groupe();
        $form = $this->createForm(GroupeType::class, $groupe);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)

            $groupe->setCreatedAt(new \DateTime());

            // 4) save the group
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($groupe);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('groupe_show');
        }

        return $this->render(
            'admin/groupe/new_groupe.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/groupe/{id}/delete", name="groupe_delete", methods="DELETE")
     */
    public function delete(Request $request, Groupe $groupe)
    {
        if ($this->isCsrfTokenValid('delete' . $groupe->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupe);
            $em->flush();
        }

        return $this->redirectToRoute('groupe_show');
    }
}
