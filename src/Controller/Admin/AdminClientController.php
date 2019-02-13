<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RechercheClientType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Types\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminClientController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(UserRepository $repository, ObjectManager $em)
    {

        $this->repository = $repository;
        $this->em = $em;
    }


    /**
     * @Route("/admin/client/show", name="client_show")
     * @Route ("/admin/client/recherche", name="recherche_client", methods="GET|POST")
     */
    public function show(PaginatorInterface $paginator, Request $request)
    {
        // Récupère tous les utilisateurs
        //$users = $this->repository->findAll();

        $form = $this->createForm(RechercheClientType::class);
        $form->handleRequest($request);

        $query = $this->repository->recherche($form['rechercheClient']->getData());
        //Pagination avec 10 users par page
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 10
        );

        //Récupérer le nombre d'utilisateurs
        $count = count($query);
        //$count = $this->repository->countRecherche();

        return $this->render('admin/client/data-tables.html.twig', [
            'users' => $users,
            'count' => $count,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/{id}/edit", name="client_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_show', [
                'id' => $user->getId()
            ]);
        }
        return $this->render('admin/client/client_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/client/new", name="client_new")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setDateInscription(new \DateTime());

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('client_show');
        }

        return $this->render(
            'admin/client/new_client_dashboard.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/{id}/delete", name="client_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('client_show');
    }

    /*

    public function rechercheAction() {
        $form = $this->createForm(RechercheClientType::class);
        return $this->render('admin/client/recherche.html.twig',[
                'form' => $form->createView()
            ]
        );
    }

*/


}
